# Visionity - Digiton WordPress Staging & Archive

Repository di backup e sincronizzazione del progetto WordPress **Visionity** ([test100.afterbit.it](https://test100.afterbit.it)).

## Contenuto del repository

- `local_staging/`: Installazione WordPress completa (temi `paragon`, child theme, plugin Elementor, e cartella `wp-content/uploads/` con le nuove immagini caricate).
- `database_visionity_test100.sql`: Dump SQL aggiornato completo di tutte le tabelle WordPress (`qhaupsn_*`), comprensivo delle ultime correzioni di percorsi, sostituzione immagini e ripristino della pagina Membership.
- `downloaded_photos/`: Immagini originali fornite dal cliente (Google Drive) per la sostituzione grafica.
- Script di migrazione, importazione e verifica automatica utilizzati per il setup e la sincronizzazione del database.

## Stato del progetto (test100.afterbit.it)
- **CMS:** WordPress + Elementor
- **Tema:** Paragon
- **Menu Principale:** Semplificato (Home, Membership, Onboarding, Contact Us)
- **Pagine Demo disattivate:** Pagine demo e template non pertinenti dismessi / passati in bozza.
- **Pagine attive verificate:**
  - `/` (Home con slider, nuove immagini e layout aggiornato)
  - `/membership/` (Attiva e funzionante)
  - `/onboarding/` (Attiva e funzionante)
  - `/contact-us-2/` (Attiva e funzionante)
