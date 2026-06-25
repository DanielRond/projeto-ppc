# Sistema de Plano Pedagogico de Curso (PPC)
### Desenvolvido por Daniel Rond de Jesus dos Santos
Este projeto e um prototipo funcional de um sistema para coordenacao e supervisao da criacao e avaliacao de cursos de graduacao, desenvolvido como parte do desafio pratico para o processo seletivo de estagio da ASTTIC/PROEG. O sistema permite que unidades academicas submetam propostas de cursos e grades curriculares, que passam por um fluxo de aprovacao tecnica ate a decisao final pela Camara de Ensino.

## Tecnologias Utilizadas

O projeto foi construido utilizando tecnologias modernas para garantir escalabilidade e integridade dos dados:
- **Laravel 11:** Framework backend para construcao da API e logica de negocio.
- **Vue.js 3:** Framework frontend utilizando a Composition API para uma interface reativa.
- **Vite:** Ferramenta de build de proxima geracao para um desenvolvimento frontend ultra-rapido.
- **MySQL:** Banco de dados relacional para persistencia de dados.
- **Spatie Laravel Model States:** Pacote utilizado para implementar uma maquina de estados robusta no fluxo de aprovacao.

## Estrutura de Dados e Logica de Negocio

### Maquina de Estados
Para garantir que as propostas de PPC sigam um fluxo rigoroso e evitar transicoes invalidas, foi implementada uma maquina de estados com os seguintes status:
- **Submitted (Submetido):** Estado inicial apos a submissao pela unidade.
- **UnderReview (Em avaliacao):** Proposta em analise por um servidor designado.
- **AdjustmentRecommended (Recomendacao de ajuste):** Proposta retornada para correcoes.
- **FinalDecision (Decisao final):** Fase de aprovacao ou reprovacao pela Camara de Ensino.

### Banco de Dados
A modelagem inclui tabelas fundamentais para o sistema acadêmico:
- **courses:** Armazena o nome, carga horaria, justificativa e o status atual da proposta.
- **subjects:** Contem as disciplinas da grade curricular, vinculadas ao curso e ao respectivo semestre.

## Pre-requisitos

Antes de iniciar, voce precisara ter instalado em sua maquina:
- PHP 8.2 ou superior.
- Composer.
- Node.js (versao 18 ou superior) e NPM.
- MySQL ou MariaDB.

## Instrucoes de Instalacao e Execucao

Siga os passos abaixo para configurar o ambiente local:

1.  **Clonar o repositorio:**
    ```bash
    git clone https://github.com/DanielRond/projeto-ppc.git
    cd projeto-ppc
    ```

2.  **Instalar dependencias do PHP:**
    ```bash
    composer install
    ```

3.  **Instalar dependencias do Javascript:**
    ```bash
    npm install
    ```

4.  **Configurar o ambiente:**
    - Copie o arquivo `.env.example` para `.env`.
    - Configure as credenciais do seu banco de dados MySQL nas variaveis `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD`.

5.  **Gerar chave da aplicacao e configurar API:**
    ```bash
    php artisan key:generate
    php artisan install:api
    ```

6.  **Executar as migrations:**
    ```bash
    php artisan migrate
    ```

7.  **Rodar o projeto:**
    - Em um terminal, inicie o servidor backend:
      ```bash
      php artisan serve
      ```
    - Em outro terminal, inicie o servidor de desenvolvimento do Vite:
      ```bash
      npm run dev
      ```

Acesse a aplicacao atraves do endereco indicado pelo comando `php artisan serve` (geralmente http://127.0.0.1:8000).

