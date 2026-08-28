// Luodaan rekisteröitymislomakkeen asiakaspuolen validointi.
const validation = new JustValidate("#signup");

validation
    // Nimi on pakollinen kenttä.
    .addField("#name", [
        {
            rule: "required"
        }
    ])
    .addField("#email", [
        // Sähköpostin pitää olla annettu ja oikeassa muodossa.
        {
            rule: "required"
        },
        {
            rule: "email"
        },
        {
            // Tarkistetaan palvelimelta, ettei sähköpostiosoite ole jo käytössä.
            validator: (value) => () => {
                return fetch("validate-email.php?email=" + encodeURIComponent(value))
                       .then(function(response) {
                           return response.json();
                       })
                       .then(function(json) {
                           return json.available;
                       });
            },
            errorMessage: "email already taken"
        }
    ])
    .addField("#password", [
        // JustValidate tarkistaa salasanan olemassaolon ja vahvuuden.
        {
            rule: "required"
        },
        {
            rule: "password"
        }
    ])
    .addField("#password_confirmation", [
        // Varmistetaan, että salasana kirjoitettiin uudelleen oikein.
        {
            validator: (value, fields) => {
                return value === fields["#password"].elem.value;
            },
            errorMessage: "Passwords should match"
        }
    ])
    .onSuccess((event) => {
        // Lähetetään lomake palvelimelle vasta onnistuneen validoinnin jälkeen.
        document.getElementById("signup").submit();
    });
    
    
    
    
    
    
    
    
    
    
    
    
    