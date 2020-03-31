"vim conf for iotcat (https://iotcat.me)

"general
set nocompatible
set autoread
set shortmess=atI
set magic
set title
set nobackup
set noerrorbells "no bell
set laststatus=2
set visualbell t_vb=
set timeoutlen=500 "wait for pending
set nowb
set noswapfile
set confirm

"folder
set foldenable
set fdm=syntax
nnoremap <space> @=((foldclosed(line('.')<0)?'zc':'zo'))<CR>

"decode
set encoding=utf-8  
set fileencodings=ucs-bom,utf-8,cp936,gb18030,big5,euc-jp,euc-kr,latin1
set fileformats=unix,dos,mac  
set termencoding=utf-8
set formatoptions+=m
set formatoptions+=B

"display
colorscheme default
syntax on
set ruler
set number
set nowrap
set showcmd
set showmode
set showmatch
set matchtime=2

"search
set hlsearch
set incsearch
set ignorecase
set smartcase


"tab settings
set expandtab
set smarttab
set shiftround
set autoindent smartindent shiftround
set shiftwidth=4
set tabstop=4
set softtabstop=4


"position
set cursorcolumn
set cursorline


"auto lang conf
filetype on
filetype plugin on
filetype indent on


"yaml config
au! BufNewFile,BufReadPost *.{yaml,yml} set filetype=yaml foldmethod=indent
autocmd FileType yaml setlocal ts=2 sts=2 sw=2 expandtab

autocmd FileType python set tabstop=4 shiftwidth=4 expandtab ai
autocmd FileType ruby set tabstop=2 shiftwidth=2 softtabstop=2 expandtab ai
autocmd BufRead,BufNew *.md,*.mkd,*.markdown set filetype=markdown.mkd
