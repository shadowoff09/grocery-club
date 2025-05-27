# Projeto AINet

## Initial Setup


1. Copy the `.env.example` file to `.env` and configure your environment variables:
```bash
cp .env.example .env
```

2. Set up your database connection in the `.env` file. Make sure to update the following variables:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=projeto_ainet
DB_USERNAME=root
DB_PASSWORD=
```

3. Install the dependencies using Composer:
```bash
composer install
```

4. Generate the application key:
```bash
# Only run this if in the file .env the APP_KEY is empty
php artisan key:generate
```
5. Check if the application can connect to the database:
```bash
php artisan db:check
```

6. Run the migrations to set up the database:
```bash
# The DB must be created before running this command
php artisan migrate:fresh
```

7. Seed the database with initial data:
```bash
# Tip: Disable real-time protection on Windows Defender to speed up the process
php artisan db:seed
```

8. Install the Frontend dependencies:
```bash
npm install
```

9. Build the assets:
```bash
npm run build
```

10. Run the application:
```bash
composer run dev
```

11. Open your browser and navigate to `http://localhost:8000` to access the application.

## Example Credentials

Board Member:\
Email: b1@mail.pt\
Password: 123

Employee:\
Email: e1@mail.pt\
Password: 123

Member:\
Email: m1@mail.pt\
Password: 123

---

## ✅ Grocery Club – Checklist de Funcionalidades

### 🔐 Autenticação & Gestão de Utilizadores
- [x] Registo de utilizadores com dados obrigatórios e opcionais
- [x] Envio de email de verificação (Mailtrap.io)
- [x] Login/Logout com email e password
- [x] Recuperação de password com email
- [x] Geração automática de cartão virtual
- [x] Pagamento da fee para ativar conta (`pending_member` → `member`)
- [x] Perfil do utilizador com edição conforme o tipo
- [x] Gestão de utilizadores (board: bloquear, cancelar, promover/demitir)
- [x] Soft delete de membros cancelados

---

### ⚙️ Configurações do Negócio
- [ ] CRUD de categorias (com imagem, soft delete se necessário)
- [ ] CRUD de produtos (preço, stock, descontos, imagem)
- [x] Definição da taxa de adesão
- [x] Definição de portes com intervalos de valor

---

### 🛍️ Catálogo & Loja
- [x] Listagem de produtos visível para todos (incluindo anónimos)
- [x] Produtos com nome, imagem, descrição, preço, desconto
- [x] Produtos fora de stock visíveis com alerta
- [x] Filtros e ordenação por categoria, nome, preço
---

### 🛒 Carrinho & Checkout
- [x] Carrinho funcional para todos os utilizadores
- [x] Atualização de quantidades, remoção de produtos
- [x] Cálculo automático de subtotal, portes e total
- [x] Preenchimento automático de NIF e morada
- [x] Restrição: só membros podem comprar
- [x] Validação de saldo suficiente no cartão
- [x] Criação da encomenda com estado “preparing”
- [x] Notificação se houver produtos sem stock
- [x] Débito automático do valor total

---

### 📦 Encomendas & Inventário
- [x] Lista de encomendas pendentes (empregados)
- [x] Marcar como "completed" → gerar PDF + enviar email
- [x] Cancelamento de encomendas pelo board + reembolso
- [ ] Visualização de stock (todos os produtos)
- [ ] Criação de ordens de reposição (manual/automático)
- [ ] Completar ordens de reposição atualiza stock
- [ ] Ajustes manuais de stock com registo

---

### 💳 Pagamentos & Cartões
- [x] Simulação de pagamento com Visa, PayPal ou MB WAY
- [x] Validações conforme o tipo de pagamento
- [x] Atualização do saldo do cartão após pagamento bem-sucedido
- [x] Visualização dos dados do cartão
- [ ] Histórico de operações com recibos PDF acessíveis

---

### 📈 Estatísticas
- [ ] Estatísticas pessoais para membros
- [ ] Estatísticas globais para board (vendas, produtos, membros, etc.)
- [ ] Tabelas e gráficos com totais, médias, etc.
- [ ] Exportação para CSV ou Excel

---

### 🔄 Funcionalidades Extra
- [x] Uso de Queues para envio de emails e geração de recibos.
- [x] Uso de Cache no catálogo (ex: produtos, categorias ou filtros) para melhorar desempenho e reduzir queries.
---
