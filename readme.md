
# Montink Test

Este projeto é uma aplicação PHP com Apache e MySQL, que também utiliza um servidor de socket. Ele pode ser executado facilmente via Docker ou manualmente.

## 📦 Requisitos

### ✅ Com Docker

* Docker
* Docker Compose

### ✅ Sem Docker

* PHP 8.1 (limite para utilizar o CodeIgniter 3)
* Apache 2.4+
* MySQL 5.7
* Node.js (para o socket server)
* Composer (para dependências PHP, se houver)
* npm/yarn (para dependências do socket)

## 🚀 Como rodar com Docker

### 1. Clone o repositório

```bash
git clone https://github.com/fdanielarruda/gerenciar-produtos.git
cd gerenciar-produtos
````

### 2. Crie um arquivo `.env`

Crie um arquivo `.env` na raiz com as variáveis necessárias:

```env
# Banco de dados
DB_HOST=db #mantenha db no docker
DB_ROOT_USER=root #mantenha root no docker
DB_ROOT_PASSWORD=sua_senha
DB_NAME=montink_db
DB_PORT=3307

# App principal
APP_URL=http://localhost
APP_PORT=8080

# Servidor de Socket
SOCKET_HOST=socket
SOCKET_PORT=3000
```

E também um `.env` dentro da pasta `socket-server` com a mesma porta:

```env
SOCKET_PORT=3000
```

### 3. Suba os containers

```bash
docker-compose up -d
```

A aplicação estará disponível em: [http://localhost:8080](http://localhost:8080)


### 4. Acesso ao banco de dados

* Host: `localhost`
* Porta: definido em `DB_PORT`
* Usuário: definido em `DB_ROOT_USER`
* Senha: definida na variável `DB_ROOT_PASSWORD`
* Banco: definido em `DB_NAME`

---

## 💻 Como rodar sem Docker

### 1. Instale e configure o ambiente manualmente

* Apache com suporte a `.htaccess` e `mod_rewrite`
* PHP 8.1 (limite da versão 3 do CodeIgniter) com extensões `mysqli` e `phpcurl`
* MySQL com o banco de dados em /init-db

### 2. Configure o Apache

Certifique-se de que o módulo `rewrite` está habilitado:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

No arquivo de configuração do Apache ou no `.htaccess`, certifique-se de que o `AllowOverride` está habilitado para o diretório do projeto.

### 3. Copie os arquivos do projeto para a pasta do servidor

```bash
sudo cp -r . /var/www/html/montink
sudo chown -R www-data:www-data /var/www/html/montink
sudo chmod -R 755 /var/www/html/montink
```

### 4. Crie um banco de dados

Crie um banco com o nome que desejar e configure a conexão em `application/config/database.php`.

### 5. Suba o socket server

Vá para a pasta `socket-server`:

```bash
cd socket-server
touch .env  #adicione o .env visto anteriormente
npm install
npm start
```

Por padrão, ele rodará em: [http://localhost:3000](http://localhost:3000)

---

## 🐳 Estrutura dos containers (Docker)

| Serviço     | Container             | Porta local | Descrição                    |
| ----------- | --------------------- | ----------- | ---------------------------- |
| App         | `montink_test_app`    | 8080        | Servidor PHP/Apache          |
| Banco MySQL | `montink_test_db`     | 3307        | Banco de dados MySQL 8.0     |
| Socket      | `montink_test_socket` | 3000        | Servidor WebSocket (Node.js) |


## 📚 Explicação do Sistema

### 🛒 Produtos

* **`/product`**
  Lista todos os produtos disponíveis.

* **`/product/create`**
  Permite a criação de novos produtos.

### 🎟️ Cupons

* **`/coupon`**
  Lista todos os cupons cadastrados.

* **`/coupon/create`**
  Permite a criação de novos cupons de desconto.

### 📦 Pedidos

* **`/order/my_requests`**
  Exibe os pedidos feitos pelo usuário logado.

### 🛍️ Carrinho

* **`/cart`**
  Interface do carrinho de compras para adicionar, remover e visualizar produtos antes de finalizar o pedido.

### 🔁 Webhook

* **`/webhook/order-status`** (POST)
  Endpoint para atualização do status de um pedido via API externa.

  **Parâmetros esperados:**

  * `id`: *inteiro* — ID do pedido a ser atualizado.
  * `status`: *string* — Novo status do pedido. Os valores permitidos são:

    * `pending`
    * `paid`
    * `completed`
    * `canceled`