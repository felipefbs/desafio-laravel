# Desafio Laravel

Este repositório contém um projeto desenvolvido com Laravel, que oferece uma API RESTful e uma interface web para gerenciamento de pedidos (Orders). A aplicação foi construída com foco em boas práticas, segurança e design minimalista, utilizando Docker e Laravel Sail para facilitar o ambiente de desenvolvimento.

## Recursos

- **API RESTful**: Endpoints para criar, ler, atualizar e excluir (soft delete) pedidos.
- **Interface Web**: Tela de listagem, criação, atualização e deleção os pedidos.

## Pré-requisitos

- [Docker](https://www.docker.com/get-started) e [Docker Compose](https://docs.docker.com/compose/install/) instalados na sua máquina.
- [Git](https://git-scm.com/) para clonar o repositório.
- Navegador web para acessar a interface.
- Ferramenta para testar a API, como [Postman](https://www.postman.com/downloads/) ou [Bruno](https://www.usebruno.com/downloads).

## Como Rodar?

1. **Verifique se o Docker e o Docker Compose estão instalados** na sua máquina.

2. **Clone este repositório**:
   ```bash
   git clone https://github.com/felipefbs/desafio-laravel.git
   ```

3. **Navegue até o diretório** onde o repositório foi clonado:

4. **Inicie os containers com o Laravel Sail**:
   ```bash
   ./vendor/bin/sail up
   ```

5. **Acesse a interface web**:
   Abra o navegador e acesse [http://localhost](http://localhost).

6. **Interaja com a API**:
   No diretório `collections` você encontrará coleções de requests para testar a API, disponíveis para [Bruno](https://www.usebruno.com/downloads) e [Postman](https://www.postman.com/downloads/).

## Tecnologias Utilizadas

- [Laravel](https://laravel.com)
- [Docker](https://www.docker.com)
- [Laravel Sail](https://laravel.com/docs/sail)
- [Blade Templates](https://laravel.com/docs/blade)

---

Criado com ❤️ por [felipefbs](http://github.com/felipefbs)  
Inspirado pela excelência em desenvolvimento!
