<< [Главная](./../../../../README.md) << [currency](./../index.md)

## <i>DELETE</i> currency/remove

Данный метод служит для удаления валюты

### Принимает

| Параметр    | Тип    | Обязательный |
|-------------|--------|--------------|
| id          | int    | да           |

### Аутентификация

Обязательно

### Запросы

**Корректный запрос:**

```json
{
  "id": 130
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
  "id": 2
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
  "id": 333
}
```

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "Could not retrieve data for 333"
}
```

</details>