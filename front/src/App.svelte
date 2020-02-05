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

			users = response.data.data.map(user => {

				let usr = {}

				fields.forEach(field => {
					usr[field.field] = user[field.resource]
				})

				return usr
			})

			//users = response.data.data
			meta = response.data.meta
		})
    }

    const changePage = page => current_page.update(() => page)

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

	let checked = []

	const multipleCheck = id => {
		let found = checked.find(item => item === id)
		if (found === undefined) {
			checked.push(id)
		} else {
			checked = checked.filter(item => item !== id)
		}
	}

	let search = ''

	const deleteSelected = () => {
		console.log('deleting ' + checked.join(', '))
	}

	const handleSearch = () => {
    	console.log(search)
	}

	let fields = [
		{
			field: 'id',
			hidden: false,
			resource: 'id'
		},
		{
			field: 'name',
			hidden: false,
			resource: 'name'
		},
		{
			field: 'email',
			hidden: false,
			resource: 'email'
		}
	]

	const hideField = field => {

		fields = fields.map(item => {
			if (item.field !== field) {
				return item;
			}
			return {
				field: field,
				hidden: !item.hidden
			}
		})


	}


</script>

<main class="mt-50">

	<input type="text" bind:value={search} />
	<button on:click={handleSearch}>Search</button>

    <table>
        <thead>
        <tr>
			<td>Selected</td>
			{#each fields as field}
				{#if !field.hidden}
					<td on:click={() => change_sort(field.field)}>{field.field}</td>
				{/if}
			{/each}
        </tr>
        </thead>
		<tbody>
		{#each users as user}
			<tr>
				<td><input type="checkbox" on:click={() => multipleCheck(user.id)}/></td>

				{#each fields as {field, hidden}}
					{#if !hidden}
						<td>{user[field]}</td>
					{/if}
				{/each}
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

	<div>
		<button on:click={deleteSelected}>Delete all selected</button>
	</div>

	{#each fields as field}
		<button on:click={() => hideField(field.field)}>Hide field {field.field}</button>
	{/each}
</main>

<style>
	.mt-50 {
		margin-top: 50px;
		margin-left: 50px;
	}
</style>