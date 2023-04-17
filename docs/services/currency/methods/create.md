<< [Главная](./../../../../README.md) << [currency](./../index.md)

## <i>PUT</i> currency/create

Данный метод служит добавления новой валюты

### Принимает

| Параметр    | Тип                   | Обязательный |
|-------------|-----------------------|--------------|
| code        | string(min 3, max 3)  | да           |
| rate        | float                 | да           |
| symbol      | string (min 1, max 1) | да           |
| companyName | string                | нет          |

### Аутентификация

Обязательно

### Запросы

**Корректный запрос:**

```json
{
  "code": "YEN",
  "rate": 130,
  "symbol": "¥"
}
```

<details open>
<summary>Ответ:</summary>

```json
{
  "isSuccess": true
}
```

</details>

---

**Пример некорректного запроса (отрицательный курс):**

```json
{
  "code": "CCC",
  "rate": -3.33,
  "symbol": "C"
}
```

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "The rate must be greater than 0.",
  "errors": {
    "rate": [
      "The rate must be greater than 0."
    ]
  }
}
```

</details>
