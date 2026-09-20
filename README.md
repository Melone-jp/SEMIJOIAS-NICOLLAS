# SEMIJOIAS NICOLLAS

Dashboard administrativo e analítico para o negócio de semijoias, desenvolvido em PHP com Bootstrap e JavaScript.

## Funcionalidades

- Login administrativo com acesso por perfil
- Dashboard executivo com métricas principais
- Filtros por data
- Módulos de pedidos, comissões, caixa e usuários
- Interface responsiva com visual corporativo
- Integração com banco MySQL e dump do projeto

## Credenciais de acesso

### Administrador
- Usuário: admin
- Senha: admin123

### Usuário comum
- Usuário: usuario
- Senha: usuario123

## Requisitos

- XAMPP com Apache e MySQL
- PHP 8+
- Browser moderno

## Execução local

1. Inicie o Apache e o MySQL no XAMPP.
2. Mantenha a pasta do projeto em `C:\xampp\htdocs\NICOLLAS`.
3. Acesse:
   - http://localhost:8000/login.php

Ou execute localmente:

```bash
cd C:\xampp\htdocs\NICOLLAS
C:\xampp\php\php.exe -S localhost:8000
```

## Estrutura principal

- `index.php` — dashboard principal
- `login.php` — tela de login
- `config.php` — configuração do banco e autenticação
- `pedidos.php` — módulo de pedidos
- `comissoes.php` — módulo de comissões
- `caixa.php` — módulo de caixa
- `usuarios.php` — módulo de usuários
- `assets/css/style.css` — estilos do painel
- `assets/js/app.js` — scripts e gráficos
- `semijoiasmr.sql` — dump do banco de dados

## Observação

Este projeto foi criado para funcionar em ambiente local com MySQL do XAMPP e pode ser adaptado para deploy em servidor web real.
