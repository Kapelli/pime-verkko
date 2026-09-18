const validation = new JustValidate("#signup");

validation
    .addField("#name", [
        {
            rule: "required"
        },
        {
            validator: (value) => value.trim().length >= 8,
            errorMessage: "Nimen pitää olla vähintään 8 merkkiä pitkä"
        },
        {
            validator: (value) => value.trim().length <= 20,
            errorMessage: "Nimi voi olla enintään 20 merkkiä pitkä"
        },
        {
            validator: (value) => /^[\p{L}\p{N}._ -]+$/u.test(value.trim()),
            errorMessage: "Nimessä saa käyttää vain kirjaimia, numeroita, välilyöntejä sekä merkkejä . _ -"
        },
        {
            validator: (value) => () => {
                return fetch("validate-username.php?name=" + encodeURIComponent(value))
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (json) {
                        return json.available;
                    });
            },
            errorMessage: "Nimi on jo käytössä"
        }
    ])
    .addField("#email", [
        {
            rule: "required"
        },
        {
            rule: "email"
        },
        {
            validator: (value) => () => {
                return fetch("validate-email.php?email=" + encodeURIComponent(value))
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (json) {
                        return json.available;
                    });
            },
            errorMessage: "Sähköpostiosoite on jo käytössä"
        }
    ])
    .addField("#password", [
        {
            rule: "required"
        },
        {
            rule: "password"
        }
    ])
    .addField("#password_confirmation", [
        {
            validator: (value, fields) => {
                return value === fields["#password"].elem.value;
            },
            errorMessage: "Salasanat eivät täsmää"
        }
    ])
    .onSuccess((event) => {
        document.getElementById("signup").submit();
    });












