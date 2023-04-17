<< [Главная](./../../../../README.md) << [currency](./../index.md)

## <i>GET</i> currency-rate/by-date

Данный метод служит получения текущего курса по коду валюты

### Принимает

| Параметр    | Тип                  | Обязательный |
|-------------|----------------------|--------------|
| code        | string(min 3, max 3) | да           |
| date        | string/date          | да           |
| companyName | string               | нет          |

### Аутентификация

Не требуется

### Запросы

**Корректный запрос:**

hostname.com/currency-rate/by-date?code=USD&date=2022-11-24


<details open>
<summary>Ответ:</summary>

```json
{
  "symbol": "$",
  "code": "USD",
  "rate": 1,
  "date": "2022-11-24",
  "companyName": "common"
}
```

</details>

---

**Пример некорректного запроса - некорректная дата:**

hostname.com/currency-rate/by-date?code=USD&date=NOT-DATE-AT-ALL

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "The date does not match the format Y-m-d.",
  "errors": {
    "date": [
      "The date does not match the format Y-m-d."
    ]
  }
}
```

</details>

**Пример некорректного запроса - некорректная дата и код валюты:**

hostname.com/currency-rate/by-date?code=***&date=DOOMSDAY

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "The code must only contain letters. (and 1 more error)",
  "errors": {
    "code": [
      "The code must only contain letters."
    ],
    "date": [
      "The date does not match the format Y-m-d."
    ]
  }
}
```

</details>