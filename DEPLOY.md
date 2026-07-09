# Publikimi online (Render — falas)

Aplikacioni është gati për deploy. Baza e të dhënave kalon në **Postgres** (Render), sepse
serverët falas s'kanë disk të përhershëm për SQLite.

## Çka duhet të dish për planin falas
- Faqja **fle pas ~15 min pa vizita** dhe zgjohet për ~30–60 sek në vizitën e parë.
- Postgres-i falas i Render **skadon pas 90 ditësh** — pastaj krijon një të ri falas ose lidh një DB
  të përhershme falas (Supabase/Neon) duke ndryshuar vetëm variablat `DB_*`.

---

## Hapi 1 — Ngarko kodin në GitHub
Repo-ja lokale është gati (git init + commit u bënë). Krijo një repo **privat** në GitHub dhe shtyje:

**Opsioni A — nga GitHub.com:** hap https://github.com/new → emri `dafi-beton` → **Private** → *Create*.
Pastaj në terminal:
```bash
cd dafi-beton
git remote add origin https://github.com/<përdoruesi-yt>/dafi-beton.git
git push -u origin main
```

**Opsioni B — me GitHub CLI:**
```bash
cd dafi-beton
gh repo create dafi-beton --private --source=. --remote=origin --push
```

## Hapi 2 — Krijo shërbimin në Render
1. Hyr te https://render.com (regjistrohu me GitHub — falas).
2. **New → Blueprint**.
3. Zgjidh repo-n `dafi-beton`. Render lexon vetë skedarin `render.yaml` dhe propozon:
   - një **Web Service** (`dafi-beton`)
   - një **Postgres** (`dafi-beton-db`)
4. Kliko **Apply**.

## Hapi 3 — Vendos dy variablat
Në **Web Service → Environment**, plotëso dy fushat që lamë bosh me qëllim:

| Variabla | Vlera |
|---|---|
| `APP_KEY` | `base64:aO5pi5Hkn4LUI0dXYxfH0NwRuDX1+3URstsQcPoHdio=` |
| `APP_URL` | linku që të jep Render, p.sh. `https://dafi-beton.onrender.com` |

> `APP_URL` vendose pas deploy-it të parë, kur ta dish linkun; pastaj bëj **Manual Deploy → Clear build cache & deploy** një herë.

Render do të ndërtojë imazhin, do të ekzekutojë migrimet + të dhënat fillestare vetë, dhe faqja del online.

## Hapi 4 — Kyçu dhe ndrysho fjalëkalimin
- Hap `https://<linku-yt>.onrender.com/admin`
- Email: `admin@dafibeton.com` · Fjalëkalimi: `dafibeton123`
- **Ndrysho fjalëkalimin menjëherë** (ose krijo përdorues të ri dhe fshij këtë).

## Përditësime më vonë
Çdo `git push` në `main` → Render ridepolon automatikisht.

## Domain yti (opsional)
Web Service → **Settings → Custom Domains** → shto `stok.dafibeton.com` dhe ndiq udhëzimet DNS.
