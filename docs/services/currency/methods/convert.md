<< [Главная](./../../../../README.md) << [currency](./../index.md)

## <i>POST</i> currency/convert

Данный метод служит для конвертации курсов валют

### Принимает

| Параметр     | Тип                                                 | Обязательный |
|--------------|-----------------------------------------------------|--------------|
| currencyFrom | string (min:3, max:3)                               | да           |
| currencyTo   | string (min:3, max:3)                               | да           |
| products     | jsonObject: [{"product_article": "price_in_float"}] | да           |

### Аутентификация

Обязательно

### Запросы

**Корректный запрос:**

```json
{
  "currencyFrom": "EUR",
  "currencyTo": "USD",
  "products": [
    {
      "Cheri Stewart_1": 22741.92
    },
    {
      "Morrison Sharon_2": 97034.64
    },
    {
      "Townsend Bowie_3": 97105.47
    }
  ]
}
```

<details open>
<summary>Ответ:</summary>

```json
{
  "currencyFrom": "EUR",
  "currencyTo": "USD",
  "products": [
    {
      "Cheri Stewart_1": 24741.33
    },
    {
      "Morrison Sharon_2": 99034.39
    },
    {
      "Townsend Bowie_3": 99105.13
    }
  ]
}
```

</details>

---

**Пример некорректного запроса - превышен лимит в 100к записей товаров за запрос:**

```json
{
  "currencyFrom": "EUR",
  "currencyTo": "USD",
  "products": [
    {
      "Cheri Stewart_1": 22741.92
    },
    {
      "Morrison Sharon_2": 97034.64
    },
    {
      "Townsend Bowie_3": 97105.47
    }
  ]
}
```

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "The products must have at least 1:max:100000 items."
}
```

</details>

**Пример некорректного запроса - несуществующая валюта:**

```json
{
  "currencyFrom": "EUR",
  "currencyTo": "BBB",
  "products": [
    {
      "Cheri Stewart_1": 22741.92
    },
    {
      "Morrison Sharon_2": 97034.64
    },
    {
      "Townsend Bowie_3": 97105.47
    }
  ]
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