import { useEffect, useState } from 'react'

type Product = {
  id: string
  name: string
  description: string
  price: number
  image: string
  price_id: string
}

function Home() {
  const [products, setProducts] = useState<Product[]>([])

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        const response = await fetch('http://localhost:8000/api/products')
        
        if (!response.ok) {
          throw new Error('Network response was not ok')
        }
    
        const data = await response.json()

        setProducts(data)
      } catch (error) {
        console.error('Erro ao buscar produtos:', error)
      }
    }    

    fetchProducts()
  }, [])

  async function handleProductCheckout(priceId: string) {
    const response = await fetch('http://localhost:8000/api/checkout', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ price_id: priceId }),
    })

    const data = await response.json()
    console.log(data)

    window.location.href = data.url
  }

  return (
    <div className="min-h-screen bg-gray-100 p-6">
      <div className="max-w-7xl mx-auto">
        <h1 className="text-3xl font-bold text-gray-800 mb-6">Nossos Produtos</h1>

        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          {products.map((product) => (
            <div key={product.id} className="bg-white rounded-2xl shadow-md p-4 hover:shadow-lg transition">
              <img
                src={product.image}
                alt={product.name}
                className="w-full h-48 object-cover rounded-xl mb-4"
              />
              <h2 className="text-xl font-semibold text-gray-700">{product.name}</h2>
              <p className="text-gray-500">{product.description}</p>
              <p className="text-lg font-bold text-indigo-600">R$ {product.price}</p>

              <button onClick={() => handleProductCheckout(product.price_id)} className="bg-black cursor-pointer text-white font-medium rounded-lg p-2 w-32 mt-2">Buy</button>
            </div>
          ))}
        </div>
      </div>
    </div>
  )
}

export default Home
