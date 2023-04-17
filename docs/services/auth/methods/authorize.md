[<< [Главная](./../../../../README.md) << [auth](./../index.md)

## <i>POST</i> auth/authorize

Данный метод служит для авторизации и получения токена

### Принимает

| Параметр | Тип          | Обязательный |
|----------|--------------|--------------|
| email    | string/email | да           |
| password | string       | да           |

### Аутентификация

Не требуется

### Запросы

**Корректный запрос:**

```json
{
  "email": "test@test.com",
  "password": "password"
}
```

<details open>
<summary>Ответ:</summary>

```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2F1dGgvcmVnaXN0ZXIiLCJpYXQiOjE2NzE3ODM4NzAsImV4cCI6MTY3MjM4ODY3MCwibmJmIjoxNjcxNzgzODcwLCJqdGkiOiJkYVhPc3dVd1dFeG5nVjA0Iiwic3ViIjoiMzg3OCIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJyb2xlcyI6W10sImF1ZCI6ImN1cnJlbmN5In0.Mz0Yg-uS3McUJZVD-D1WoquVZ2eXQRMAmiax01URCyo",
  "validThrough": "2022-12-30 08:24:30"
}
```

</details>

---

**Пример некорректного запроса - рандомные данные:**

```json
{
  "email": "123",
  "password": "234"
}
```

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "The email must be a valid email address.",
  "errors": {
    "email": [
      "The email must be a valid email address."
    ]
  }
}
```

</details>


**Пример некорректного запроса - несуществующий пользователь:**

```json
{
  "email": "aaa@aaa.com",
  "password": "1212341"
}
```

<details open>
<summary>Ответ:</summary>

```json
{
  "message": "Could not retrieve data for aaa@aaa.com"
}
```

</details>