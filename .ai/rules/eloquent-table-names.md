---
glob: app/Models/**
title: Always set $table explicitly on Spanish-named models
---

Eloquent's default table-name inference pluralizes the class name in **English** (`Proveedor` → `proveedors`, `MovimientoCaja` → `movimiento_cajas`). Every model in this app has a Spanish name and a Spanish-pluralized migration table (`proveedores`, `movimientos_caja`), so the default guess is wrong every time.

This has already caused two production-blocking bugs (500 errors, "Base table or view not found") in the Proveedores and Caja modules, only caught by clicking through the feature in a browser after migrating.

**Rule**: every new model must declare `protected $table = '...';` matching the migration's table name — don't rely on Eloquent's inferred name. Check this explicitly before considering a new model/migration pair done, since the app boots and routes register fine even when the table name is wrong; it only breaks on the first real query.
