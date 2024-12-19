# Projeto: API Financeira - Desafio Técnico Objective

## Descrição

Esta é uma API para gerenciamento de contas bancárias e transações financeiras.

Ela oferece funcionalidades como:
- Consulta de contas e filtro pelo numero da conta [account_number]
- Criação, atualização de informações e exclusão de contas
- Transações bancárias (Crédito, Débito e PIX)
- Consulta de transações ordenadas da mais recente à mais antiga

## Tecnologias Utilizadas

- PHP 8.1+
- Laravel 10
- MySQL (ou outro banco de dados suportado pelo Laravel)
- Docker
- Swagger para documentação da API

---

## Configuração do Ambiente

### Requisitos

- Docker e Docker Compose instalados
- Composer instalado
- Git instalado

### Passos para configuração

Clone o repositório:

```bash
git clone https://github.com/LucasOlmedo/objective-desafio-tecnico.git
cd objective-desafio-tecnico/
```

Configure as variáveis de ambiente:

Crie um arquivo `.env` baseado no exemplo:

```bash
cp .env.example .env
```

Atualize as informações no arquivo `.env` conforme sua configuração local, especialmente as seções de banco de dados e URLs da aplicação.

Sugestão abaixo:

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=api
DB_USERNAME=root
DB_ROOT_PASSWORD=root
````

Construa os contêineres Docker:

```bash
docker-compose up -d
```

Entre no contêiner da aplicação:

```bash
docker-compose exec app bash
```

Dentro do `contêiner`, altere as permissões para escrita nos arquivos:

```
chown 1000:1000 -Rf storage/ bootstrap/cache
chmod 777 -Rf storage/ bootstrap/cache
```

Dentro do `contêiner`, instale as dependências:

```bash
composer install
```

Dentro do `contêiner`, gere a chave da aplicação:

```bash
php artisan key:generate
```

Dentro do `contêiner`, execute as migrações para configurar o banco de dados:

```bash
php artisan migrate:fresh --seed
```

Dentro do `contêiner`, gere a documentação da API (Swagger):

```bash
php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"

php artisan l5-swagger:generate
```

## Acesse a documentação da API em:
## `http://localhost/api/documentation`

---

## Endpoints Principais

### Contas

- **POST /account**: Criação de uma nova conta
- **GET /account**: Listagem de todas as contas
- **GET /account?account_number=1111**: Filtra os registros pelo número da conta

(Outros Endpoints `account` disponíveis na documentação)

### Transações

- **GET /transaction**: Lista todas as transações ordenadas pela mais recente
- **POST /transaction**: Criação de uma nova transação (crédito ou débito)

(Outros Endpoints `transaction` disponíveis na documentação)

---

## Rodando os Testes

Execute os testes unitários para garantir que o sistema está funcionando corretamente:

Entre no contêiner da aplicação:

```bash
docker-compose exec app bash
```

Dentro do `contêiner`, execute os testes:

```bash
php artisan test
```