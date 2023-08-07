import random

def generate_cpf():
    cpf = [random.randint(0, 9) for _ in range(9)]

    # Calcula o primeiro dígito verificador
    total = sum(digit * (10 - i) for i, digit in enumerate(cpf))
    digit1 = 11 - (total % 11)
    cpf.append(digit1 if digit1 < 10 else 0)

    # Calcula o segundo dígito verificador
    total = sum(digit * (11 - i) for i, digit in enumerate(cpf))
    digit2 = 11 - (total % 11)
    cpf.append(digit2 if digit2 < 10 else 0)

    return ''.join(map(str, cpf))

# Gera um CPF válido
cpf = generate_cpf()
print("CPF gerado:", cpf)

