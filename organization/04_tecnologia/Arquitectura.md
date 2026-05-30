# Arquitectura

## Objetivo

Definir cómo se conectarán las partes principales del sistema para crear una demo funcional para clínicas dentales.

---

# Flujo general

Paciente escribe por WhatsApp o landing.

↓

El mensaje llega a Twilio o formulario web.

↓

n8n recibe el evento.

↓

n8n procesa el mensaje y lo envía al modelo IA.

↓

El sistema clasifica la intención del paciente.

↓

Si es pregunta frecuente, responde.

↓

Si quiere agendar, consulta disponibilidad.

↓

Backend guarda paciente, lead y cita en Supabase.

↓

El sistema envía confirmación.

↓

Dashboard muestra leads y citas.

---

# Componentes

## Frontend

Responsables: David / Montero

Tecnología:

- Angular

Funciones:

- Landing page
- Formulario de contacto
- Vista de agenda
- Dashboard básico

---

## Backend

Responsables: Jorqui / Montero

Tecnología:

- Node.js

Funciones:

- API de pacientes
- API de citas
- API de leads
- API de tratamientos
- Validaciones
- Conexión con Supabase

---

## Database

Responsable: Jorqui

Tecnología:

- Supabase

Funciones:

- Guardar clínicas
- Guardar pacientes
- Guardar leads
- Guardar citas
- Guardar conversaciones

---

## Automatización

Responsable: Montero

Tecnología:

- n8n
- Twilio

Funciones:

- Recibir mensajes
- Procesar intención
- Enviar respuestas
- Enviar recordatorios
- Conectar WhatsApp con backend

---

## IA

Tecnología:

- ChatGPT / Claude

Funciones:

- Responder FAQ
- Clasificar intención
- Ayudar a guiar al paciente
- Generar respuestas controladas

---

# Reglas importantes

- La IA no debe diagnosticar.
- La IA no debe recetar.
- La IA no debe pedir datos clínicos sensibles.
- La IA debe escalar a humano cuando no esté segura.
- El sistema debe guardar todo con clinic_id para poder usarse con varias clínicas.

---

# Notas relacionadas

- [[Base de datos]]
- [[Producto MVP]]
- [[Chatbot FAQ]]
- [[Agendador]]
- [[Flujo del paciente]]
