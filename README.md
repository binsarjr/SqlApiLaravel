# SqlApiLaravel

It starts with the boss's request. If there are suggestions for the priority section, please make an issues

## Parameter(Json Body,Query String, Form Data)

| Name         | type     | description |
|--------------|-----------|------------|
| sql   | `string` | Do your magic query |
| type | `read` \| `write` | Just Read or Write |
| bindings | `array` \| `object` | data binding from query |
| connection | `name` | connection name that has been set|



## Read
```bash
curl --location --request POST 'http://localhost:8000/api/query' \
--header 'Content-Type: application/json' \
--data-raw '{
    "sql": "SELECT id, nama FROM public.users where id = ?",
    "bindings": [1],
    "type":"read"
}'
```

## Write
```bash
curl --location --request POST 'http://localhost:8000/api/query' \
--header 'Content-Type: application/json' \
--data-raw '{
    "sql": "INSERT INTO public.users (name) VALUES (:name)",
    "bindings": {"name": "Abang binsar"},
    "type":"write"
}'
```

## Connection
```bash
curl --location --request POST 'http://localhost:8000/api/query' \
--header 'Content-Type: application/json' \
--data-raw '{
    "sql": "SELECT * FROM public.users where id = :id",
    "bindings": {"id": 1},
    "type":"read",
    "connection": "pgsql"
}'
```