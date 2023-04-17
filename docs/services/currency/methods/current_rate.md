<< [Главная](./../../../../README.md) << [currency](./../index.md)

## <i>GET</i> currency-rate/current

Данный метод служит получение текущего курса по коду валюты

### Принимает

| Параметр    | Тип                  | Обязательный |
|-------------|----------------------|--------------|
| code        | string(min 3, max 3) | да           |
| companyName | string               | нет          |

### Аутентификация

Обязательно

### Запросы

**Корректный запрос:**

hostname.com/currency-rate/current?code=USD


<details open>
<summary>Ответ:</summary>

```json
{
  "symbol": "$",
  "code": "USD",
  "rate": 1,
  "date": "2022-12-23",
  "companyName": "common"
}
```

</details>

---

**Пример некорректного запроса - несуществующая валюта:**

hostname.com/currency-rate/current?code=PPP

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "Could not retrieve data for PPP"
}
```

</details>

**Пример некорректного запроса - слишком длинный код валюты:**

hostname.com/currency-rate/current?code=THIS_STRING_IS_TOO_LONG

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "The code must not be greater than 3 characters."
}
```

</details>