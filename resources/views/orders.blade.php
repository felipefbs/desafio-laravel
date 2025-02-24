<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gerenciar Orders</title>
    <style>
        body {
            background-color: #e0f7fa;
            font-family: Arial, sans-serif;
            color: #0d47a1;
            margin: 0;
            padding: 20px;
        }

        .actions {
            display: flex;
            justify-content: space-evenly;
            flex-direction: column;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        form div {
            margin-bottom: 10px;
        }

        label {
            display: inline-block;
            width: 150px;
        }

        input,
        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: calc(100% - 160px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #0d47a1;
            color: #fff;
        }

        button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            background-color: #0d47a1;
            color: #fff;
            cursor: pointer;
            margin-right: 5px;
        }

        button:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Gerenciar Pedidos</h1>
        <form id="order-form">
            <input type="hidden" id="order-id" name="order_id" value="">

            <div>
                <label for="client_name">Nome do Cliente:</label>
                <input type="text" id="client_name" name="client_name" required>
            </div>
            <div>
                <label for="order_date">Data do Pedido:</label>
                <input type="date" id="order_date" name="order_date" required>
            </div>
            <div>
                <button type="submit" id="submit-btn">Criar Pedido</button>
                <button type="button" id="cancel-btn" style="display: none;">Cancelar Edição</button>
            </div>
        </form>

        <table id="orders-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Data do Pedido</th>
                    <th>Data de Entrega</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const apiUrl = '/api/orders';
            const form = document.getElementById('order-form');
            const orderIdInput = document.getElementById('order-id');
            const clientNameInput = document.getElementById('client_name');
            const orderDateInput = document.getElementById('order_date');
            const deliveryDateInput = document.getElementById('delivery_date');
            const statusSelect = document.getElementById('status');
            const submitBtn = document.getElementById('submit-btn');
            const cancelBtn = document.getElementById('cancel-btn');
            const ordersTableBody = document.querySelector('#orders-table tbody');

            window.markDelivered = async function (id) {
                const today = new Date().toISOString().split('T')[0];

                const response = await fetch(apiUrl + '/' + id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: 'delivered', delivery_date: today })
                });
                if (response.ok) {
                    loadOrders();
                } else {
                    const errorData = await response.json();
                    alert('Erro ao atualizar status para "Entregue": ' + (errorData.error || 'Erro desconhecido'));
                }
            };

            window.markCanceled = async function (id) {
                const response = await fetch(apiUrl + '/' + id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: 'canceled' })
                });
                if (response.ok) {
                    loadOrders();
                } else {
                    const errorData = await response.json();
                    alert('Erro ao atualizar status para "Cancelado": ' + (errorData.error || 'Erro desconhecido'));
                }
            };

            async function loadOrders() {
                const response = await fetch(apiUrl);
                const orders = await response.json();
                ordersTableBody.innerHTML = '';
                orders.forEach(order => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
            <td>${order.id}</td>
            <td>${order.client_name}</td>
            <td>${order.order_date}</td>
            <td>${order.delivery_date ? order.delivery_date : 'N/A'}</td>
            <td>${order.status}</td>
            <td class="actions">
              <button onclick="markDelivered('${order.id}')">Entregue</button>
              <button onclick="markCanceled('${order.id}')">Cancelar</button>
              <button onclick="deleteOrder('${order.id}')">Apagar</button>
            </td>
          `;
                    ordersTableBody.appendChild(tr);
                });
            }

            loadOrders();

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                const orderId = orderIdInput.value;
                const data = {
                    client_name: clientNameInput.value,
                    order_date: orderDateInput.value,
                };
                let response;
                if (orderId) {
                    const response = await fetch(apiUrl + '/' + orderId, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });
                } else {
                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });
                }

                if (response.ok) {
                    resetForm();
                } else {
                    const errorData = await response.json();
                    alert('Erro ao atualizar: ' + (errorData.error || 'Erro desconhecido'));
                }

                loadOrders();
            });

            function resetForm() {
                orderIdInput.value = '';
                clientNameInput.value = '';
                orderDateInput.value = '';
                submitBtn.textContent = 'Criar Pedido';
                cancelBtn.style.display = 'none';
            }


            window.editOrder = async function (id) {
                const response = await fetch(apiUrl + '/' + id);
                if (response.ok) {
                    const order = await response.json();
                    orderIdInput.value = order.id;
                    clientNameInput.value = order.client_name;
                    orderDateInput.value = order.order_date;
                    deliveryDateInput.value = order.delivery_date;
                    statusSelect.value = order.status;
                    submitBtn.textContent = 'Atualizar Order';
                    cancelBtn.style.display = 'inline-block';
                } else {
                    alert('Erro ao carregar a Order para edição.');
                }
            };

            window.deleteOrder = async function (id) {
                if (confirm('Tem certeza que deseja apagar esta Order?')) {
                    const response = await fetch(apiUrl + '/' + id, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                    if (response.ok) {
                        loadOrders();
                    } else {
                        const errorData = await response.json();
                        alert('Erro ao apagar: ' + (errorData.error || 'Erro desconhecido'));
                    }
                }
            };

            cancelBtn.addEventListener('click', resetForm);
        });
    </script>
</body>

</html>