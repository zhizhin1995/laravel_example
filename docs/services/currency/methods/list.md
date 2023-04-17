<< [Главная](./../../../../README.md) << [currency](./../index.md)

## <i>GET</i> currency/list

Данный метод служит для получения списка валют

### Аутентификация

Обязательно

### Запросы

**Корректный запрос:**

hostname.com/currency/list

<details open>
<summary>Ответ:</summary>

```json
{
  "list": [
    {
      "id": 1,
      "symbol": "$",
      "code": "USD",
      "company": "common",
      "project": "b2c",
      "created_at": "2022-12-24 00:00:00",
      "updated_at": "2022-12-24 00:00:00"
    },
    {
      "id": 2,
      "symbol": "X",
      "code": "XXX",
      "company": "common",
      "project": "b2c",
      "created_at": "2022-12-24 00:00:00",
      "updated_at": "2022-12-24 00:00:00"
    },
    {
      "id": 3,
      "symbol": "Y",
      "code": "YYY",
      "company": "common",
      "project": "b2c",
      "created_at": "2022-12-24 00:00:00",
      "updated_at": "2022-12-24 00:00:00"
    }
  ]
}
```

</details>

---

**Пример некорректного запроса - недостаточно прав:**

hostname.com/currency/list

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "You have no permission to do this"
}
```

</details>

**Пример некорректного запроса - токен авторизации не был передан:**

hostname.com/currency/list

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "Token not provided"
}
```

</details>