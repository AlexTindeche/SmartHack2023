import secrets

#Generarea unei parole de minim 10 caractere care contine cel putin
#o litera mare, o litera mica, o cifra si un caracter special

def generate_password(length = 0):
    password = []
    password.append(secrets.choice("ABCDEFGHIJKLMNOPQRSTUVWXYZ"))
    password.append(secrets.choice("abcdefghijklmnopqrstuvwxyz"))
    password.append(secrets.choice("0123456789"))
    password.append(secrets.choice("!@#$%^&*()_+=-"))
    for i in range(abs(length) + 6):
        password.append(secrets.choice("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+=-"))
    secrets.SystemRandom().shuffle(password)
    return "".join(password)
#Aceasta functie poate fi folosita pentru a genera parole
# de lungime variabila
print(generate_password(10))

#Genereaza un string URL-safe de (cel putin) 32 de caractere
def url_safe_token(length = 0):
    return secrets.token_urlsafe(abs(length) + 32)
print(url_safe_token(10))
#Poate fi folosit pentru a genera un token de autentificare sau pentru a genera un salt pentru o parola

#Genereaza un token hexazecimal de (cel putin) 32 de caractere hexazecimale
def hex_token(length = 0):
    return secrets.token_hex(abs(length) + 32)
print(hex_token(10))
#Poate fi folosit pentru a genera un token de autentificare sau pentru a genera un salt pentru o parola

#Verifica daca 2 secvente sunt identice sau nu, minimizand riscul unui timing attack
def compare_digest(a, b):
    return secrets.compare_digest(a, b)
print(compare_digest("abc", "abc"))
print(compare_digest("abc", "abd"))
#Poate fi folosit pentru a compara parolele sau tokenurile de autentificare

#Genereaza o cheie fluida binara care ulterior sa poate fi folosita pentru riptarea unui mesaj de 100 de caractere
def token_bytes():
    return secrets.token_bytes(100)
print(token_bytes())

#Stocheaza parole folosind un modul/librarie care sa ofere un nivel suficient de securitate

import bcrypt
def hash_password(password):
    return bcrypt.hashpw(password, bcrypt.gensalt(14))
def check_password(password, hashed):
    return bcrypt.checkpw(password, hashed)
print(hash_password(b"abc"))
#Am ales bcrypt, pentru ca este un algoritm de hashing lent, ceea ce inseamna ca este mai greu de spart