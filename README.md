# 1.Sistemos paskirtis

Projekto tikslas – palengvinti bei pagreitinti pardavimų aikštelėse parduodamų automobilių prekybą.

Veikimo principas - pačią kuriamą platformą sudaro dvi dalys: internetinė aplikacija, kuria naudosis darbuotojai ir administratorius bei aplikacijų programavimo sąsaja (angl. trump. API).

Pardavėjas, norėdamas naudotis šia platforma, prisiregistruos prie internetinės aplikacijos ir galės pridėti naujus savo parduodamus automobilius bei valdyti juos. Pardavėjai galės matyti visuose miestuose esančias pardavimų aikšteles bei matytų jų būseną, laisvų vietų skaičių. Administratorius tvirtintų pardavėjų registracijas, peržiūrėtų jų pridėtus automobilius, nes prieš paskelbimą viešai, automobilis turėtų būti patvirtintas.

# 2.Sistemos dokumentacija

1.
## Naudotojo metodai

| API metodas | POST |
| --- | --- |
| Paskirtis | Prisijungti |
| Kelias iki metodo (angl. route) | /api/user/login |
| Užklausos struktūra | {"username": "admin@test.com","password": "admin"} |
| Užklausos „Header" dalis | Content-Type: application/json |
| Atsakymo struktūra | {"token": "token"} |
| Atsakymo kodas | 200 |
| Galimi klaidų kodai | 404 |

| API metodas | POST |
| --- | --- |
| Paskirtis | Registracija |
| Kelias iki metodo (angl. route) | /api/user/register |
| Užklausos struktūra | {"email": "admin@test.com","password": "admin"} |
| Užklausos „Header" dalis | Content-Type: application/json |
| Atsakymo struktūra | - |
| Atsakymo kodas | 201 |
| Galimi klaidų kodai | 409 |

| API metodas | GET |
| --- | --- |
| Paskirtis | Naudotojo informacija |
| Kelias iki metodo (angl. route) | /api/user |
| Užklausos struktūra |
|
| Užklausos „Header" dalis | Authorization: „Bearer {token}" |
| Atsakymo struktūra | {"email": [admin@test.com](mailto:admin@test.com), "roles": ["ROLE\_ADMIN"]} |
| Atsakymo kodas | 201 |
| Galimi klaidų kodai | 401 |

| API metodas | GET |
| --- | --- |
| Paskirtis | Naudotojo įkelti automobiliai |
| Kelias iki metodo (angl. route) | /api/user/vehicles |
| Užklausos struktūra |
|
| Užklausos „Header" dalis | Authorization: „Bearer {token}" |
| Atsakymo struktūra | {"id": 2,"make": "BMW","model": "318D","dateOfManufacture": "2017-01-01T00:00:00+00:00","fuelType": "diesel","gearBox": "manual","engineCapacity": 2000,"price": 15000,"cityId": 5,"lotId": 2} |
| Atsakymo kodas | 200 |
| Galimi klaidų kodai | 401 |

1.
## Miesto metodai

| API metodas | GET |
| --- | --- |
| Paskirtis | Gauti visus miestus |
| Kelias iki metodo (angl. route) | /api/city |
| Užklausos struktūra |
|
| Užklausos „Header" dalis |
|
| Atsakymo struktūra | {"id": 5,"name": "Klaipėda"} |
| Atsakymo kodas | 200 |
| Galimi klaidų kodai |
|

| API metodas | GET |
| --- | --- |
| Paskirtis | Gauti miestą |
| Kelias iki metodo (angl. route) | /api/city/{id} |
| Užklausos struktūra |
|
| Užklausos „Header" dalis |
|
| Atsakymo struktūra | {"id": 5,"name": "Klaipėda"} |
| Atsakymo kodas | 200 |
| Galimi klaidų kodai | 404 |

| API metodas | POST |
| --- | --- |
| Paskirtis | Pridėti miestą |
| Kelias iki metodo (angl. route) | /api/city/ |
| Užklausos struktūra | {"name": "Klaipėda"} |
| Užklausos „Header" dalis | Authorization: „Bearer {token}" |
| Atsakymo struktūra |
|
| Atsakymo kodas | 200 |
| Galimi klaidų kodai | 401 |

| API metodas | PUT |
| --- | --- |
| Paskirtis | Redaguoti miestą |
| Kelias iki metodo (angl. route) | /api/city/{id} |
| Užklausos struktūra | {"name": "Klaipėda"} |
| Užklausos „Header" dalis | Authorization: „Bearer {token}" |
| Atsakymo struktūra |
|
| Atsakymo kodas | 204 |
| Galimi klaidų kodai | 401, 404 |

| API metodas | DELETE |
| --- | --- |
| Paskirtis | Ištrinti miestą |
| Kelias iki metodo (angl. route) | /api/city/{id} |
| Užklausos struktūra |
|
| Užklausos „Header" dalis | Authorization: „Bearer {token}" |
| Atsakymo struktūra |
|
| Atsakymo kodas | 204 |
| Galimi klaidų kodai | 401, 404 |

1.
## Aikštelės metodai

| API metodas | GET |
| --- | --- |
| Paskirtis | Gauti visas aikšteles |
| Kelias iki metodo (angl. route) | /api/city/{id}/lot |
| Užklausos struktūra |
|
| Užklausos „Header" dalis |
|
| Atsakymo struktūra | {"id": 2,"name": "Klaipėdos aikštelė 1","address": "Taikos g. 5","phone": "863000000","maxNumberOfCars": 10,"email": "aldas@test.com","city": "Klaipėda","cityId": 5} |
| Atsakymo kodas | 200 |
| Galimi klaidų kodai |
|

| API metodas | GET |
| --- | --- |
| Paskirtis | Gauti aikštelę |
| Kelias iki metodo (angl. route) | /api/city/{id}/lot/{id} |
| Užklausos struktūra |
|
| Užklausos „Header" dalis |
|
| Atsakymo struktūra | {"id": 2,"name": "Klaipėdos aikštelė 1","address": "Taikos g. 5","phone": "863000000","maxNumberOfCars": 10,"email": "aldas@test.com","city": "Klaipėda","cityId": 5} |
| Atsakymo kodas | 200 |
| Galimi klaidų kodai | 404 |

| API metodas | POST |
| --- | --- |
| Paskirtis | Pridėti aikštelę |
| Kelias iki metodo (angl. route) | /api/city/{id}/lot |
| Užklausos struktūra | {"id": 2,"name": "Klaipėdos aikštelė 1","address": "Taikos g. 5","phone": "863000000","maxNumberOfCars": 10,"email": "aldas@test.com","city": "Klaipėda","cityId": 5} |
| Užklausos „Header" dalis | Authorization: „Bearer {token}" |
| Atsakymo struktūra |
|
| Atsakymo kodas | 200 |
| Galimi klaidų kodai | 401 |

| API metodas | PUT |
| --- | --- |
| Paskirtis | Redaguoti aikštelę |
| Kelias iki metodo (angl. route) | /api/city/{id}/lot/{lotId} |
| Užklausos struktūra | {"id": 2,"name": "Klaipėdos aikštelė 1","address": "Taikos g. 5","phone": "863000000","maxNumberOfCars": 10,"email": "aldas@test.com","city": "Klaipėda","cityId": 5} |
| Užklausos „Header" dalis | Authorization: „Bearer {token}" |
| Atsakymo struktūra |
|
| Atsakymo kodas | 204 |
| Galimi klaidų kodai | 401, 404 |

| API metodas | DELETE |
| --- | --- |
| Paskirtis | Ištrinti aikštelę |
| Kelias iki metodo (angl. route) | /api/city/{id}/lot/{lotId} |
| Užklausos struktūra |
|
| Užklausos „Header" dalis | Authorization: „Bearer {token}" |
| Atsakymo struktūra |
|
| Atsakymo kodas | 204 |
| Galimi klaidų kodai | 401, 404 |

1.
## Automobilių metodai

| API metodas | GET |
| --- | --- |
| Paskirtis | Gauti visus automobilius |
| Kelias iki metodo (angl. route) | /api/city/{id}/lot/{lotId}/vehicle |
| Užklausos struktūra |
|
| Užklausos „Header" dalis |
|
| Atsakymo struktūra | {"id": 2,"make": "BMW","model": "318D","dateOfManufacture": "2017-01-01T00:00:00+00:00","fuelType": "diesel","gearbox": "manual","engineCapacity": 2000,"price": 15000} |
| Atsakymo kodas | 200 |
| Galimi klaidų kodai |
|

| API metodas | GET |
| --- | --- |
| Paskirtis | Gauti automobilį |
| Kelias iki metodo (angl. route) | /api/city/{id}/lot/{lotId}/vehicle/{vehicleId} |
| Užklausos struktūra |
|
| Užklausos „Header" dalis |
|
| Atsakymo struktūra | {"id": 2,"make": "BMW","model": "318D","dateOfManufacture": "2017-01-01T00:00:00+00:00","fuelType": "diesel","gearbox": "manual","engineCapacity": 2000,"price": 15000} |
| Atsakymo kodas | 200 |
| Galimi klaidų kodai | 404 |

| API metodas | POST |
| --- | --- |
| Paskirtis | Pridėti automobilį |
| Kelias iki metodo (angl. route) | /api/city/{id}/lot/{lotId}/vehicle |
| Užklausos struktūra | {"id": 2,"make": "BMW","model": "318D","dateOfManufacture": "2017-01-01T00:00:00+00:00","fuelType": "diesel","gearbox": "manual","engineCapacity": 2000,"price": 15000} |
| Užklausos „Header" dalis | Authorization: „Bearer {token}" |
| Atsakymo struktūra |
|
| Atsakymo kodas | 200 |
| Galimi klaidų kodai | 401 |

| API metodas | PUT |
| --- | --- |
| Paskirtis | Redaguoti automobilį |
| Kelias iki metodo (angl. route) | /api/city/{id}/lot/{lotId}/vehicle/{vehicleId} |
| Užklausos struktūra | {"id": 2,"make": "BMW","model": "318D","dateOfManufacture": "2017-01-01T00:00:00+00:00","fuelType": "diesel","gearbox": "manual","engineCapacity": 2000,"price": 15000} |
| Užklausos „Header" dalis | Authorization: „Bearer {token}" |
| Atsakymo struktūra |
|
| Atsakymo kodas | 204 |
| Galimi klaidų kodai | 401, 404 |

| API metodas | DELETE |
| --- | --- |
| Paskirtis | Ištrinti automobilį |
| Kelias iki metodo (angl. route) | /api/city/{id}/lot/{lotId}/vehicle/{vehicleId} |
| Užklausos struktūra |
|
| Užklausos „Header" dalis | Authorization: „Bearer {token}" |
| Atsakymo struktūra |
|
| Atsakymo kodas | 204 |
| Galimi klaidų kodai | 401, 404 |