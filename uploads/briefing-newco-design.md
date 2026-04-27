# BRIEFING DE DESIGN — Atualizações Newco Brazil

**Site alvo:** https://newcobrazil.com/
**Objetivo:** Tornar o site mais atrativo para compradores estrangeiros (foco Itália / Europa / EUA), reforçar premiumness, qualificar tecnologia e logística.
**Regra de ouro:** **NÃO alterar a estrutura atual.** Apenas (a) ADICIONAR novas seções nos pontos indicados e (b) fazer ajustes pontuais de UX (header de idioma).

**Escopo desta entrega:** apenas novas seções institucionais e ajuste de header. **Nada será alterado dentro dos menus existentes (Produtos, etc.).** Submenus, fichas de produto e download de fichas técnicas serão tratados em uma próxima fase, separadamente.

---

## 1. CONTEXTO DA EMPRESA (essencial para tom)

A **Newco Brazil** é uma indústria de ingredientes funcionais em pó (frutas tropicais, adoçantes naturais, alimentos funcionais) localizada em Varginha-MG. Atende mercado interno e exportação, com foco crescente em Itália e Europa.

**Três pilares de marca:** naturalidade • funcionalidade • sabor.

**Posicionamento:** ingrediente premium, técnico, B2B industrial. NÃO é varejo, NÃO é fruta seca, NÃO é commodity. É **ingrediente pronto para uso industrial**, com tecnologia de processo e certificações.

**Tom desejado:** sóbrio, técnico-confiante, premium, internacional. Nada de "vibe orgânico hippie". Pense em fornecedor sério para indústria de alimentos europeia.

**Certificações que devem aparecer com selos visuais:** APEX PEIEX, GMO Free, GMP, HACCP, VEGAN.

---

## 2. ESTRUTURA ATUAL (preservar 100%)

```
Início → Quem Somos → O que Fazemos → Produtos → Contato
```

Tudo isso continua existindo, no mesmo lugar, com o mesmo conteúdo. **A seção Produtos não será tocada nesta fase.** As novidades são encaixadas entre seções existentes.

## 3. ESTRUTURA PROPOSTA (após ajustes)

```
[HEADER com botão de idioma fixo e visível]
↓
Início (mantido)
Quem Somos (mantido)
[NOVA] Tecnologia Spray Drying
[NOVA] Galeria da Estrutura
O que Fazemos (mantido)
Produtos (mantido — sem alterações nesta fase)
[NOVA] Localização Estratégica & Porto Seco
[NOVA] Rastreabilidade, Economia Circular & Desperdício Zero
Contato (mantido)
```

---

# AJUSTE 0 — HEADER: Botão de troca de idioma visível

## Situação atual
O site já tem versão em inglês (`/en/index.php`), mas o switch fica nas bandeirinhas no rodapé — invisível para um comprador estrangeiro que cai na home em português e vai embora em 3 segundos.

## O que fazer
- Mover o seletor de idioma para o **header fixo (top-right)**, presente em todas as seções.
- Usar o padrão **PT | EN** (texto, não bandeira — bandeiras geram problemas de UX e geopolíticos).
- **Idealmente adicionar IT (italiano)** em uma fase 2, dado o foco da Newco no mercado italiano. Por ora, criar a infraestrutura preparada para receber `IT` quando os textos forem traduzidos.
- **Detecção automática de idioma** pelo navegador na primeira visita (com fallback para PT-BR).
- Manter as bandeiras no rodapé como redundância, sem problema.

## Aceite
Comprador italiano/americano abre `newcobrazil.com` e em até 2 segundos vê e entende como mudar para inglês.

---

# SEÇÃO NOVA 1 — Tecnologia Spray Drying

## Onde encaixar
**Entre "Quem Somos" e "O que Fazemos".** É a ponte entre identidade e oferta — é a tecnologia que justifica a qualidade.

## Objetivo
Educar o comprador estrangeiro sobre o processo, mostrar que a Newco é técnica (não artesanal), e ancorar o argumento de **preservação organoléptica e nutricional** que destrava o discurso de premium.

## Estrutura visual sugerida

**1. Bloco hero da seção** (ocupa largura total, fundo escuro ou off-white premium)
- Título: **"Spray Drying — A tecnologia por trás da qualidade"**
- Subtítulo: "Transformamos frutas e ingredientes líquidos em pó preservando sabor, aroma, cor e nutrientes."

**2. Bloco explicativo (2 colunas)**
- Coluna esquerda: animação/ilustração simples do processo (atomização → câmara de ar quente → ciclone → pó). Pode ser SVG animado ou ilustração estática limpa. **Não usar ícones genéricos do Font Awesome.**
- Coluna direita: texto curto e técnico (200-250 palavras) explicando o processo. Sugestão de copy abaixo.

**Copy sugerido (PT — adaptar para EN):**

> A secagem por atomização (spray drying) é um dos métodos mais eficientes da indústria alimentícia e farmacêutica para transformar líquidos em pó fino e seco. O processo é praticamente instantâneo: o produto é atomizado em microgotículas dentro de uma câmara de ar quente controlado, e a evaporação acontece em frações de segundo.
>
> O resultado é um pó esférico, uniforme e altamente solúvel — que mantém intactas as propriedades nutricionais e organolépticas (sabor, aroma e cor) da matéria-prima original.

**3. Três cards horizontais — "Por que spray drying é a melhor escolha"**

| Card | Título | Texto curto |
|---|---|---|
| 1 | **Baixo dano térmico** | A exposição ao calor é tão rápida que a temperatura da partícula não atinge níveis que degradam o produto. Vitaminas, antioxidantes e compostos sensíveis chegam preservados ao seu produto final. |
| 2 | **Microencapsulação** | Compostos bioativos são envolvidos em uma matriz protetora que defende contra oxidação e volatilização. Sabor, cor e função nutricional preservados por 18 a 24 meses. |
| 3 | **Solubilidade total** | Pó esférico e uniforme que reidrata instantaneamente, recuperando a qualidade do produto in natura. Ideal para uso industrial em bebidas, gelatos, iogurtes, suplementos. |

**4. Faixa de prova final**
> "Concentração mínima 2x superior à polpa • Shelf-life 18 a 24 meses • Sem cadeia fria • 100% solúvel"

## Aceite
Um responsável de R&D italiano lê esta seção e entende o processo, os benefícios técnicos e por que isso importa para a formulação dele — sem precisar abrir Google.

---

# SEÇÃO NOVA 2 — Galeria da Estrutura

## Onde encaixar
**Entre "Tecnologia Spray Drying" e "O que Fazemos"** — funciona como prova visual de que a tecnologia descrita acima acontece em uma instalação real, moderna e profissional.

## Objetivo
Mostrar visualmente que a Newco é uma indústria moderna, limpa, profissional. Hoje as fotos do site são antigas e prejudicam a percepção de qualidade.

## Estrutura sugerida
- **Galeria de 6 a 10 fotos** (carrossel ou grid), todas em alta resolução.
- Categorias visuais:
  1. Linha de produção (spray drying em operação).
  2. Laboratório de análises e pesquisa.
  3. Embalagem e armazenagem.
  4. Time técnico em ação.
  5. Fachada/área externa moderna.
  6. Detalhes de produto (pó, embalagem industrial).
- **Tratamento visual:** fotos com paleta consistente, iluminação clean, sem aspecto "anos 2010". Estética próxima de fornecedores europeus de ingredientes premium.

## Pendência do cliente
> "As fotos e imagens são muito antigas e portanto, estão de alguma forma desqualificadas..."

**Cliente vai providenciar fotos novas.** Construir o componente de galeria agora com placeholders profissionais (pode usar fotos de stock temporárias, claramente sinalizadas no código como temporárias). Quando as fotos novas chegarem, substituir.

**Briefing fotográfico sugerido para o cliente:** formato 3:2 ou 16:9, alta resolução (mín. 2400px no lado maior), iluminação branca/neutra, sem pessoas com EPI velho ou ambiente bagunçado.

## Aceite
A galeria comunica em 5 segundos: "esta é uma operação industrial moderna, organizada e profissional".

---

# SEÇÃO NOVA 3 — Localização Estratégica & Porto Seco

## Onde encaixar
**Após a seção Produtos**, antes da seção Contato. Funciona como argumento de "por que comprar do Brasil, especificamente da Newco" — é um diferencial logístico fortíssimo que hoje não aparece.

## Objetivo
Para um comprador europeu, "Brasil" é abstrato. Mostrar que Varginha tem infraestrutura logística de classe mundial, com Porto Seco que desburocratiza a exportação, derruba a objeção "será que vai chegar?".

## Estrutura visual sugerida

**1. Bloco hero da seção**
- Título: **"Localizada no coração logístico do Brasil"**
- Subtítulo: "Varginha-MG: hub estratégico com Porto Seco alfandegado e acesso direto aos principais portos e rodovias do país."

**2. Mapa interativo ou ilustração**
- Mapa do Brasil destacando Varginha + raio de 400 km cobrindo SP, RJ, MG, BH, Campinas.
- Marcador adicional: Porto Seco Sul de Minas.
- Linhas tracejadas indicando rotas até portos de Santos, Rio de Janeiro, Itajaí.

**3. Quatro cards de destaque (números fortes)**

| Card | Destaque numérico | Texto |
|---|---|---|
| 1 | **R$ 25 bi** | em volume de cargas movimentado anualmente pelo Porto Seco Sul de Minas |
| 2 | **65% do PIB nacional** | está em um raio de 400 km de Varginha — incluindo SP, RJ, BH e Campinas |
| 3 | **+500 empresas** | atendidas pelo complexo logístico, incluindo Eurofarma, Boticário e exportadores premium |
| 4 | **US$ 2 bi** | em exportações em 2025 — Varginha lidera o ranking de exportação de Minas Gerais |

**4. Bloco "Por que isso importa para você" (3 bullets)**

- **Desburocratização:** Porto Seco alfandegado processa Receita Federal, Receita Estadual e Anvisa em um único ponto, com fluxo otimizado para exportação.
- **Velocidade:** infraestrutura completa de classificação, separação e expedição no mesmo complexo, reduzindo lead-time logístico.
- **Confiabilidade:** região consolidada como o maior polo exportador de Minas Gerais, com volume crescente e fluxo estabelecido para Europa, EUA e Ásia.

## Fonte de credibilidade
Adicionar uma linha de fonte ao final: *"Dados: Porto Seco Sul de Minas, Secretaria de Comércio Exterior, balança comercial 2025."*

## Aceite
Um comprador italiano lê esta seção e entende que: (a) Varginha não é uma cidadezinha qualquer, (b) tem infraestrutura logística testada, (c) o produto vai chegar com agilidade e conformidade documental.

---

# SEÇÃO NOVA 4 — Rastreabilidade, Economia Circular & Desperdício Zero

## Onde encaixar
**Logo após a seção de Localização Estratégica.** A lógica é: "produzimos com qualidade, escoamos com infraestrutura, e fazemos isso de forma sustentável e rastreável".

## Objetivo
Comunicar premiumness pela ótica ESG — argumento decisivo para mercado europeu. Conectar a operação da Newco (indústria limpa, sem desperdício) com a região (Varginha sustentável, Selo Cristal, "Marco Zero do Café").

## Estrutura visual sugerida

**1. Bloco hero**
- Título: **"Premium não é só qualidade. É origem rastreável e processo sustentável."**
- Subtítulo curto explicando que a Newco e Varginha são polos reconhecidos de práticas ESG na indústria de ingredientes.

**2. Duas colunas: "Rastreabilidade" e "Economia Circular"**

### Coluna 1 — Rastreabilidade & Origem
- **Origem mapeada:** parcerias diretas com produtores da região, com identificação geográfica e técnicas de análise de origem (metabolômica, isótopos — referência: Epamig/Varginha).
- **Padronização lote a lote:** controle de qualidade via classificação eletrônica e processos certificados (GMP, HACCP).
- **Documentação completa:** ficha técnica, certificado de origem, relatórios de análise — disponíveis para todo lote exportado.
- **Referência regional:** Varginha é "Marco Zero do Circuito Nacional do Café", com tecnologia QR Code de rastreabilidade do grão da origem ao consumidor.

### Coluna 2 — Economia Circular & Desperdício Zero
- **Indústria limpa:** todo o material que entra na linha de produção é aproveitado. Exemplo: do café verde, extraímos óleo, ácido clorogênico, trigonelina, aminoácidos — sem sobras.
- **Aproveitamento integral:** inteligência de produção que transforma "resíduo" em coproduto comercial.
- **Iluminação LED, otimização de água, lanternins para luz natural** — práticas adotadas no complexo logístico regional.
- **Selo Cristal (Caixa Econômica, 2025):** Varginha foi reconhecida por práticas de ESG, criando um ecossistema sustentável que beneficia toda a cadeia.

**3. Faixa de fechamento**
> "Compre ingredientes que carregam o que o mercado europeu exige: origem rastreável, processo certificado, impacto ambiental controlado."

## Aceite
Um comprador europeu termina a seção convencido de que comprar da Newco é compatível com — e até reforça — sua agenda ESG e suas exigências de compliance.

---

# AJUSTES TRANSVERSAIS (todas as seções)

## Tom e idioma
- **PT-BR:** técnico-confiante, premium, sem coloquialismos.
- **EN:** profissional, direto, focado em ROI e benefícios mensuráveis.
- **IT (fase 2):** formal ("Lei"), caloroso, focado em construção de confiança.

## SEO
Cada nova seção deve ter:
- H1/H2 semânticos com palavras-chave.
- Meta-description única (PT e EN).
- Alt-text em todas as imagens.
- Schema.org markup quando aplicável (Organization, FAQ).

**Palavras-chave prioritárias (EN):**
`fruit powder supplier`, `spray dried fruit powder`, `tropical fruit ingredients`, `Brazilian açaí supplier`, `bulk natural ingredients`, `private label fruit powder`.

## Performance
- Imagens em WebP com fallback JPEG.
- Lazy loading em todas as imagens fora do viewport inicial.
- Manter tempo de carregamento abaixo de 2,5s no Lighthouse mobile.

## Mobile-first
- Toda nova seção deve funcionar perfeitamente em mobile. Compradores B2B internacionais cada vez mais navegam de mobile.
- Botões de CTA com tamanho mínimo de 44x44px.

## Acessibilidade
- Contraste WCAG AA mínimo.
- Navegação por teclado em todos os componentes interativos (galeria, mapa).
- Alt-text descritivo (não "imagem1.jpg").

---

# RESUMO DE PENDÊNCIAS DO CLIENTE (não bloqueiam o início do projeto)

| Item | Status | Ação do Design |
|---|---|---|
| Fotos novas das instalações | Cliente vai providenciar | Construir galeria com fotos temporárias, swap depois |
| Tradução para italiano (IT) | Não iniciada | Preparar estrutura multilíngue para receber IT em fase 2 |

---

# PRIORIZAÇÃO SUGERIDA DE EXECUÇÃO

**Sprint 1 — sem dependência de assets:**
1. Botão de idioma no header.
2. Seção Spray Drying.
3. Seção Localização Estratégica & Porto Seco.
4. Seção Rastreabilidade & Sustentabilidade.

**Sprint 2 — depende de fotos novas:**
5. Galeria da Estrutura (placeholder na entrega 1, fotos finais quando o cliente entregar).

---

# CHECKLIST FINAL DE ACEITE DO PROJETO

- [ ] Estrutura original do site preservada integralmente — seção Produtos intocada.
- [ ] Botão de idioma visível no header em todas as seções.
- [ ] Seção Spray Drying explicando processo + 3 benefícios.
- [ ] Galeria da Estrutura com 6+ fotos (placeholders ok na entrega 1).
- [ ] Seção Localização Estratégica com 4 destaques numéricos.
- [ ] Seção Rastreabilidade & Economia Circular.
- [ ] Site responsivo e funcional em mobile.
- [ ] Performance Lighthouse mobile ≥ 90.
- [ ] Versão EN completa e revisada por nativo.

---

**Dúvidas técnicas ou de escopo:** retornar com questões específicas antes de iniciar o desenvolvimento. Não assumir comportamento que não esteja aqui.
