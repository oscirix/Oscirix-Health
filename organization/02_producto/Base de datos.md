# Base de datos

## Objetivo

Diseñar la estructura mínima para guardar clínicas, pacientes, leads, citas, conversaciones y configuraciones necesarias para el MVP.

El sistema debe estar pensado para funcionar con varias clínicas, no solo con una.

---

# Tablas MVP

## clinics

Guarda la información de cada clínica cliente.

- id
- name
- address
- phone
- email
- instagram
- website
- created_at
- updated_at

---

## clinic_settings

Guarda configuraciones específicas de cada clínica.

- id
- clinic_id
- opening_hours
- appointment_duration
- timezone
- whatsapp_number
- active
- created_at
- updated_at

---

## patients

Guarda información básica del paciente o lead.

- id
- clinic_id
- name
- phone
- email
- created_at
- updated_at

---

## leads

Guarda pacientes interesados que todavía no necesariamente tienen cita.

- id
- clinic_id
- patient_id
- source
- treatment_interest
- status
- notes
- created_at
- updated_at

## Estados posibles del lead

- new
- contacted
- interested
- scheduled
- no_response
- lost
- converted

---

## treatments

Guarda los tratamientos que ofrece la clínica.

- id
- clinic_id
- name
- description
- estimated_duration
- active
- created_at
- updated_at

## Ejemplos

- limpieza
- ortodoncia
- implantes
- diseño de sonrisa
- endodoncia
- valoración general

---

## appointments

Guarda las citas agendadas.

- id
- clinic_id
- patient_id
- treatment_id
- appointment_date
- appointment_time
- status
- notes
- created_at
- updated_at

## Estados posibles de cita

- scheduled
- confirmed
- rescheduled
- cancelled
- completed
- no_show

---

## conversations

Guarda mensajes del paciente y respuestas del sistema.

- id
- clinic_id
- patient_id
- channel
- message
- response
- created_at

## Canales posibles

- whatsapp
- instagram
- website
- phone

---

## reminders

Guarda recordatorios enviados.

- id
- clinic_id
- appointment_id
- patient_id
- reminder_type
- status
- scheduled_for
- sent_at
- created_at

## Tipos de recordatorio

- 48_hours_before
- 24_hours_before
- 4_hours_before
- post_appointment_review

---

## users

Usuarios internos del sistema.

- id
- clinic_id
- name
- email
- role
- created_at
- updated_at

## Roles

- super_admin
- clinic_admin
- secretary
- doctor

---

# Tablas futuras

No necesarias para el MVP inicial.

## reviews

- id
- clinic_id
- patient_id
- appointment_id
- rating
- comment
- google_review_requested
- created_at

## campaigns

- id
- clinic_id
- name
- type
- status
- created_at

## call_logs

- id
- clinic_id
- patient_id
- phone
- summary
- status
- created_at

---

# Relaciones importantes

- Una clínica puede tener muchos pacientes.
- Una clínica puede tener muchos tratamientos.
- Un paciente puede tener muchos leads.
- Un paciente puede tener muchas citas.
- Una cita pertenece a un paciente.
- Una cita pertenece a un tratamiento.
- Una conversación pertenece a un paciente.
- Un recordatorio pertenece a una cita.

---

# Notas relacionadas

- [[Producto MVP]]
- [[Flujo del paciente]]
- [[Dashboard]]
- [[Chatbot FAQ]]
- [[Agendador]]
- [[Arquitectura]]