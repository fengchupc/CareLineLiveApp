# CareLineLiveApp

### schema tables relationship:
- Carers and shifts: one-to-many (one Carer can have multiple shifts)
- Clients and shifts: one-to-many (one client can have multiple shifts)
- Shifts and Carers: many-to-one (one shift belongs to one Carer)
- Shifts and Clients: many-to-one (one shift belongs to one client)