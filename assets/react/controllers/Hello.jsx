import React, { useState } from 'react';

export default function Hello(props) {
  const [count, setCount] = useState(0);

  return (
    <div className="card rounded p-3 bg-slate-500">
      <h3>{props.name} !</h3>
      <p>Compteur: {count}</p>
      <button
        className="mt-10 flex w-50 items-center justify-center rounded-md border border-transparent
            bg-indigo-600 px-8 py-3 text-base font-medium text-white
            hover:bg-indigo-700 focus:ring-2
            focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden"
        onClick={() => setCount(count + 1)}
      >
        Incrémenter
      </button>
    </div>
  );
}
