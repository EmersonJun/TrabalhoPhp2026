# 💼 MyWallet — Gestor Financeiro Pessoal

Projeto acadêmico desenvolvido em PHP para a disciplina de Desenvolvimento Web.

https://youtu.be/-vhMgKrubg4?si=E7-QHig3te8FTK2Y
## 🚀 Como executar

### Pré-requisitos
- PHP 7.4 ou superior (com extensão `session` habilitada)
- Servidor local: XAMPP, WAMP, Laragon ou PHP built-in server

### Executar com PHP built-in server
```bash
cd mywallet
php -S localhost:8000
```
Acesse: http://localhost:8000

### Credenciais de acesso
| Usuário | Senha     |
|---------|-----------|
| admin   | admin123  |
| user    | user123   |

---

## 📁 Estrutura do Projeto

```
mywallet/
├── index.php          → Dashboard principal (receitas, despesas, saldo, formulário)
├── login.php          → Autenticação com password_hash() e password_verify()
├── logout.php         → Encerramento de sessão
├── historico.php      → Histórico de transações com % de despesas
├── sessao.php         → Gerenciamento central de sessão e dados
├── funcoes.php        → Funções auxiliares reutilizáveis
└── includes/
    ├── header.php     → HTML inicial + CSS completo
    ├── footer.php     → Fechamento HTML
    └── nav.php        → Menu de navegação

```

## ✅ Requisitos atendidos

- [x] Página de login com `password_hash()` e `password_verify()`
- [x] Sessão iniciada após login bem-sucedido
- [x] Dashboard com saldo total, receitas e despesas
- [x] Formulário POST para nova transação (Nome, Valor, Tipo)
- [x] Persistência com `$_SESSION` durante a navegação
- [x] Página de histórico com `foreach` e todas as transações
- [x] Cálculo aritmético de saldo (receitas − despesas)
- [x] **Bônus:** Cálculo percentual de cada despesa frente ao total
- [x] Controle de acesso — redirecionamento para login se não autenticado
- [x] Botão "Zerar Mês" para limpar o histórico da sessão
- [x] Remoção individual de transações
- [x] Organização com `require_once` / `include`
- [x] Mínimo de 5 arquivos PHP distintos
- [x] Interface responsiva e visualmente profissional (CSS puro)
- [x] Funções reutilizáveis com parâmetros e retorno
- [x] Manipulação de arrays associativos e strings
- [x] Estruturas condicionais e laços de repetição

## 👥 Integrantes
- Integrante 1 — [Emerson]
- Integrante 2 — [Taynara]
