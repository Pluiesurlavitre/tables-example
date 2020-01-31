<script>
    export let name
	import { writable } from 'svelte/store'

	import axios from 'axios'

    let users = []
    let meta = {}

	let order_field = 'id'
	let order_value = 'ASC'

    export const current_page = writable(1)

	function range(size, startAt = 0) {
		return [...Array(size).keys()].map(i => i + startAt);
	}

    $: {
        let response = axios.get(`http://localhost:8000/api/users?page=${$current_page}&field=${order_field}&order=${order_value}`)
		.then(response => {
			users = response.data.data
			meta = response.data.meta
		})
    }

    const changePage = page => {
    	current_page.update(() => page)
	}

	const change_sort = field => {

    	if (order_field === field) {
    		if (order_value === 'ASC') {
    			order_value = 'DESC'
			} else {
				order_value = 'ASC'
			}
		} else {
			order_field = field
			order_value = 'ASC'
		}
		changePage(1)
	}


</script>

<main class="mt-50">
    <table>
        <thead>
        <tr>
			<td on:click={() => change_sort('id')}>ID</td>
			<td on:click={() => change_sort('name')}>Name</td>
			<td on:click={() => change_sort('email')}>Email</td>
        </tr>
        </thead>
		<tbody>
		{#each users as user}
			<tr>
				<td>{user.id}</td>
				<td>{user.name}</td>
				<td>{user.email}</td>
			</tr>
		{/each}
		</tbody>
    </table>

	<div class="pagination">

		{#each range(meta.last_page, 1) as page}

		<button on:click={() => changePage(page)}>{page}</button>


		{/each}


	</div>

	<div>
		Current page: {$current_page}
	</div>


</main>

<style>
	.mt-50 {
		margin-top: 50px;
	}
</style>