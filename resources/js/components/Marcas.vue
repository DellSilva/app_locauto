<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <card-component titulo="Busca de marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp"
                                    texto-ajuda="digite o ID da marca desejada.">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp"
                                        placeholder="ID da marca" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da Marca" id="inputNome" id-help="nomeHelp"
                                    texto-ajuda="digite a marca desejada.">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp"
                                        placeholder="Marca" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end"
                            @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Ralação de marcas">
                    <template v-slot:conteudo>
                        <table-component :dados="marcas.data"
                            :visualizar="{visivel: true, dataBsToggle: 'modal', dataBsTarget: '#modalMarcaVisualizar'}"
                            :editar="{visivel: true, dataBsToggle: 'modal', dataBsTarget: '#modalMarcaEditar'}"
                            :excluir="{visivel: true, dataBsToggle: 'modal', dataBsTarget: '#modalMarcaExcluir'}"
                            :titulos="{
                                id: {titulo: 'ID', tipo: 'texto'},
                                nome: {titulo: 'Nome', tipo: 'texto'},
                                imagem: {titulo: 'Imagem', tipo: 'imagem'}
                            }"></table-component>
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col mb-10">
                                <paginate-component>
                                    <li v-for="l, key in marcas.links" :key="key"
                                        :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                                    data-bs-target="#modalMarca">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>

            </div>
        </div>

        <modal-component id="modalMarca" titulo='Adicionar Marca'>

            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso"
                    v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca"
                    v-if="transacaoStatus == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="col mb-3">
                    <input-container-component titulo="Nome da Marca:" id="novoNome" id-help="novoNomeHelp"
                        texto-ajuda="Informe a marca desejada.">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp"
                            placeholder="Marca" v-model="nomeMarca">
                    </input-container-component>
                </div>
                <div class="col mb-3">
                    <input-container-component titulo="Imagem da Marca:" id="novoImagem" id-help="novoImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp"
                            placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>

        <modal-component id="modalMarcaVisualizar" titulo='Visualizar Marca'>

            <template v-slot:alertas></template>

            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Noma da marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>

                <input-container-component titulo="Imagem">
                    <img :src="'storage/'+$store.state.item.imagem" v-if="$store.state.item.imagem">
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>

        </modal-component>

        <modal-component id="modalMarcaExcluir" titulo='Remover Marca'>

            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Operação realizada com sucesso"
                    :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'">
                </alert-component>
                <alert-component tipo="danger" titulo="Erro na operação" :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Noma da marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>

                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="excluir()"
                    v-if="$store.state.transacao.status != 'sucesso'">Excluir</button>
            </template>

        </modal-component>

        <modal-component id="modalMarcaEditar" titulo='Editar Marca'>

            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Operação realizada com sucesso"
                    :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'">
                </alert-component>
                <alert-component tipo="danger" titulo="Erro na operação" :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="col mb-3">
                    <input-container-component titulo="Nome da Marca:" id="editarNome" id-help="editarNomeHelp"
                        texto-ajuda="Informe a marca desejada.">
                        <input type="text" class="form-control" id="editarNome" aria-describedby="editarNomeHelp"
                            placeholder="Marca" v-model="$store.state.item.nome">
                    </input-container-component>
                </div>
                <div class="col mb-3">
                    <input-container-component titulo="Imagem da Marca:" id="editarImagem" id-help="editarImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control-file" id="editarImagem"
                            aria-describedby="editarImagemHelp" placeholder="Selecione uma imagem"
                            @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="editar()">Atualizar</button>
            </template>
        </modal-component>

    </div>
</template>

<script>
import axios from 'axios'
import Paginate from './Paginate.vue'
export default {
    components: { Paginate },
    computed: {
        token() {
            let token = document.cookie.split(';').find(indice => {
                return indice.includes('token=')
            })

            token = token.split('=')[1]
            token = 'Bearer ' + token

            return token
        }
    },
    data() {
        return {
            urlBase: 'http://127.0.0.1:8000/api/marca',
            urlPaginacao: '',
            urlFiltro: '',
            nomeMarca: '',
            arquivoImagem: [],
            transacaoStatus: '',
            transacaoDetalhes: [],
            marcas: { data: [] },
            busca: { id: '', nome: '' }
        }
    },
    methods: {
        editar() {

            let formData = new FormData();
            formData.append('_method', 'patch')
            formData.append('nome', this.$store.state.item.nome)

            if (this.arquivoImagem[0]) {
                formData.append('imagem', this.arquivoImagem[0])
            }

            let url = this.urlBase + '/' + this.$store.state.item.id

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Autorization': this.token
                }
            }

            axios.post(url, formData, config)
                .then(response => {
                    this.$store.state.transacao.status = 'sucesso'
                    this.$store.state.transacao.mensagem = 'Registro de marca atualizado com sucesso!'

                    //limpar o campo de seleção de arquivos
                    atualizarImagem.value = ''
                    this.carregarLista()
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro'
                    this.$store.state.transacao.mensagem = errors.response.data.message
                    this.$store.state.transacao.dados = errors.response.data.errors
                })
        },
        excluir() {
            let confirmacao = confirm('Tem certerza que deseja excluir essa Marca?')

            if (!confirmacao) {
                return false;
            }

            let formData = new FormData();
            formData.append('_method', 'delete')

            let config = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }

            let url = this.urlBase + '/' + this.$store.state.item.id

            console.log(this.$store.state.transacao)
            axios.post(url, formData, config)
                .then(response => {
                    this.$store.state.transacao.status = 'sucesso'
                    this.$store.state.transacao.mensagem = response.data.msg
                    this.carregarLista()
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro'
                    this.$store.state.transacao.mensagem = errors.response.data.erro
                })
        },
        pesquisar() {

            let filtro = ''

            for (let chave in this.busca) {

                if (this.busca[chave]) {
                    if (filtro != '') {
                        filtro += ";"
                    }
                    filtro += chave + ':ilike:' + this.busca[chave]
                }
                console.log(filtro)
            }

            if (filtro != '') {
                this.urlPaginacao = 'page=1'
                this.urlFiltro = '&filtro=' + filtro
            } else {
                this.urlFiltro = ''
            }

            this.carregarLista()
        },
        paginacao(l) {

            if (l.url) {
                this.urlPaginacao = l.url.split('?')[1]
                this.carregarLista()
            }



        },
        carregarLista() {

            let config = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }


            let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

            axios.get(url, config)
                .then(response => {
                    this.marcas = response.data
                    // console.log(this.marcas)
                })
                .catch(errors => {
                    console.log(errors)
                })

        },
        carregarImagem(e) {
            this.arquivoImagem = e.target.files
        },
        salvar() {

            let formData = new FormData();
            formData.append('nome', this.nomeMarca)
            formData.append('imagem', this.arquivoImagem[0])

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }
            axios.post(this.urlBase, formData, config)
                .then(response => {
                    this.transacaoStatus = 'adicionado'
                    this.transacaoDetalhes = {
                        mensagem: 'ID do registro ' + response.data.id
                    }
                    console.log(response)
                })
                .catch(errors => {
                    this.transacaoStatus = 'erro'
                    this.transacaoDetalhes = {
                        mensagem: errors.response.data.message,
                        dados: errors.response.data.errors
                    }
                })
        }
    },
    mounted() {
        this.carregarLista()
    }

}
</script>