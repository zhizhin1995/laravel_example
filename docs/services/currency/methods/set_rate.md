<< [Главная](./../../../../README.md) << [currency](./../index.md)

## <i>PUT</i> currency-rate/set-rate

Данный метод служит для установления курса валют

### Принимает

| Параметр    | Тип                   | Обязательный |
|-------------|-----------------------|--------------|
| code        | string (min:3, max:3) | да           |
| rate        | float                 | да           |
| companyName | string                | нет          |

### Аутентификация

Обязательно

### Запросы

**Корректный запрос:**

```json
{
  "code": "EUR",
  "rate": 1.1
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

**Пример некорректного запроса - недостаточно прав:**

```json
{
  "code": "EUR",
  "rate": 1.12
}
```

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "You have no permission to do this"
}
```

</details>

**Пример некорректного запроса - несуществующая валюта:**

```json
{
  "code": "BBB",
  "rate": 1.4
}
```

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "Could not retrieve data for BBB"
}
```

</details>