<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'MyWallet' ?> — Gestão Financeira</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:          #0f172a;
            --surface:     #1e293b;
            --surface2:    #ffffff;
            --border:      #e2e8f0;
            --text:        #1e293b;
            --text-light:  #64748b;
            --text-inv:    #f8fafc;
            --green:       #16a34a;
            --green-light: #dcfce7;
            --red:         #dc2626;
            --red-light:   #fee2e2;
            --blue:        #2563eb;
            --blue-light:  #dbeafe;
            --accent:      #6366f1;
            --radius:      12px;
            --shadow:      0 1px 3px rgba(0,0,0,.10), 0 4px 16px rgba(0,0,0,.06);
            --shadow-lg:   0 10px 40px rgba(0,0,0,.18);
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        .navbar {
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,.3);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: .5rem;
            color: var(--text-inv);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .nav-links { display: flex; gap: 1rem; }

        .nav-link {
            color: #94a3b8;
            text-decoration: none;
            font-size: .9rem;
            padding: .35rem .75rem;
            border-radius: 6px;
            transition: all .2s;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--text-inv);
            background: rgba(255,255,255,.1);
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: #94a3b8;
            font-size: .875rem;
        }

        .btn-logout {
            background: var(--red);
            color: #fff;
            border: none;
            padding: .35rem .85rem;
            border-radius: 6px;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s;
        }
        .btn-logout:hover { background: #b91c1c; }

        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .card {
            background: var(--surface2);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
            font-size: 1rem;
            color: var(--text);
        }

        .card-body { padding: 1.5rem; }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .summary-card {
            background: var(--surface2);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border-left: 4px solid transparent;
        }
        .summary-card.receitas { border-left-color: var(--green); }
        .summary-card.despesas { border-left-color: var(--red); }
        .summary-card.saldo    { background: var(--blue); color: #fff; border-left: none; }

        .summary-label {
            font-size: .8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-light);
            margin-bottom: .5rem;
        }
        .summary-card.saldo .summary-label { color: rgba(255,255,255,.8); }

        .summary-value {
            font-size: 1.75rem;
            font-weight: 700;
        }
        .summary-card.receitas .summary-value { color: var(--green); }
        .summary-card.despesas .summary-value { color: var(--red); }
        .summary-card.saldo .summary-value    { color: #fff; }

        .form-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr 1fr; }
            .form-grid .btn-add { grid-column: 1 / -1; }
        }

        .form-group { display: flex; flex-direction: column; gap: .4rem; }

        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: var(--text-light);
        }

        .form-input, .form-select {
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: .65rem .9rem;
            font-size: .95rem;
            color: var(--text);
            background: #fff;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            width: 100%;
        }
        .form-input:focus, .form-select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
        }

        .btn-add {
            background: var(--text);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .7rem 1.5rem;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
            white-space: nowrap;
        }
        .btn-add:hover { background: #334155; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            border: none;
            border-radius: 8px;
            padding: .55rem 1.1rem;
            font-size: .875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--border);
            color: var(--text);
        }
        .btn-outline:hover { background: #f1f5f9; }
        .btn-danger { background: var(--red); color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-primary { background: var(--blue); color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }

        .table-wrapper { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .9rem;
        }

        thead th {
            text-align: left;
            padding: .85rem 1rem;
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-light);
            border-bottom: 1.5px solid var(--border);
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .15s;
        }
        tbody tr:hover { background: #f8fafc; }
        tbody tr:last-child { border-bottom: none; }

        tbody td {
            padding: .9rem 1rem;
            color: var(--text);
            vertical-align: middle;
        }

        .td-date { color: var(--text-light); font-size: .8rem; }
        .td-desc { font-weight: 600; }

        .badge {
            display: inline-block;
            padding: .2rem .65rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 700;
        }
        .badge-receita { background: var(--green-light); color: var(--green); }
        .badge-despesa { background: var(--red-light);   color: var(--red); }

        .td-value { font-weight: 700; text-align: right; }
        .td-value.positivo { color: var(--green); }
        .td-value.negativo { color: var(--red); }

        .td-pct { color: var(--text-light); font-size: .8rem; text-align: right; }

        .btn-remove {
            background: none;
            border: none;
            cursor: pointer;
            color: #cbd5e1;
            transition: color .2s;
            padding: .25rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
        }
        .btn-remove:hover { color: var(--red); }

        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: var(--text-light);
        }
        .empty-state svg { margin-bottom: 1rem; opacity: .3; }
        .empty-state p { font-size: .95rem; }

        .alert {
            border-radius: 8px;
            padding: .85rem 1.1rem;
            margin-bottom: 1.25rem;
            font-size: .9rem;
            font-weight: 500;
        }
        .alert-danger  { background: var(--red-light);  color: #991b1b; }
        .alert-success { background: var(--green-light); color: #166534; }

        .history-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        .history-header h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
        }
        .history-actions { display: flex; gap: .6rem; }

        .view-history-wrap {
            display: flex;
            justify-content: center;
            margin-top: 1.25rem;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #6366f1 0%, #3b82f6 50%, #8b5cf6 100%);
        }

        .login-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }

        .login-card-header {
            background: var(--surface);
            text-align: center;
            padding: 2.5rem 2rem 2rem;
            color: #fff;
        }
        .login-card-header svg { margin-bottom: .75rem; }
        .login-card-header h1 { font-size: 1.75rem; font-weight: 800; }
        .login-card-header p  { color: #94a3b8; font-size: .875rem; margin-top: .25rem; }

        .login-card-body { padding: 2rem; }

        .login-footer {
            text-align: center;
            padding: 1rem 2rem 1.5rem;
            font-size: .78rem;
            color: var(--text-light);
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #6366f1, #3b82f6);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .85rem;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity .2s;
            margin-top: .5rem;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .btn-login:hover { opacity: .9; }
    </style>
</head>
<body>
