This repo is about outputting all drinks ordered by name with some additional information.

After you have cloned the repository:

- install [composer](https://getcomposer.org) dependencies  
  ```composer install```

- create sqlite db on file system  
  ```php bin/console doctrine:database:create```

- run migrations  
  ```php bin/console doctrine:migrations:migrate```

- import drinks
  ```php bin/console app:import-drinks```

- list drinks  
  ```app:list-drinks-order-by-name```

---

- Start web server  
  ```symfony server:start```
- open http://127.0.0.1:8000 (or similar) in browser

- drinks listing page  
  ```http://127.0.0.1:8000/drinks```