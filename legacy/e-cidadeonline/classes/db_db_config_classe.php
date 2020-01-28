<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_config
class cl_db_config { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $codigo = 0; 
   var $nomeinst = null; 
   var $ender = null; 
   var $munic = null; 
   var $uf = null; 
   var $telef = null; 
   var $ident = 0; 
   var $tx_banc = 0; 
   var $numbanco = null; 
   var $url = null; 
   var $logo = null; 
   var $figura = null; 
   var $dtcont_dia = null; 
   var $dtcont_mes = null; 
   var $dtcont_ano = null; 
   var $dtcont = null; 
   var $diario = 0; 
   var $pref = null; 
   var $vicepref = null; 
   var $fax = null; 
   var $cgc = null; 
   var $cep = null; 
   var $tpropri = 'f'; 
   var $tsocios = 'f'; 
   var $prefeitura = 'f'; 
   var $bairro = null; 
   var $numcgm = 0; 
   var $codtrib = null; 
   var $tribinst = 0; 
   var $segmento = 0; 
   var $formvencfebraban = 0; 
   var $numero = 0; 
   var $nomedebconta = null; 
   var $db21_tipoinstit = 0; 
   var $db21_ativo = 0; 
   var $db21_regracgmiss = 0; 
   var $db21_regracgmiptu = 0; 
   var $db21_codcli = 0; 
   var $nomeinstabrev = null; 
   var $db21_usasisagua = 'f'; 
   var $db21_codigomunicipoestado = 0; 
   var $db21_datalimite_dia = null; 
   var $db21_datalimite_mes = null; 
   var $db21_datalimite_ano = null; 
   var $db21_datalimite = null; 
   var $db21_criacao_dia = null; 
   var $db21_criacao_mes = null; 
   var $db21_criacao_ano = null; 
   var $db21_criacao = null; 
   var $db21_compl = null; 
   var $email = null; 
   var $db21_imgmarcadagua = 0; 
   var $db21_esfera = 0; 
   var $db21_tipopoder = 0; 
   var $db21_codtj = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 codigo = int4 = Cod. Instituição 
                 nomeinst = varchar(80) = Nome da Instituição 
                 ender = varchar(80) = endereço da instituição 
                 munic = varchar(40) = Municipio da instituição 
                 uf = char(2) = Unidade Federativa da Instituição 
                 telef = char(11) = Telefone 
                 ident = int4 = identidade 
                 tx_banc = float8 = taxa bancaria 
                 numbanco = varchar(10) = numero do banco 
                 url = varchar(200) = url 
                 logo = varchar(100) = logo 
                 figura = varchar(100) = figura 
                 dtcont = date = data da contabilidade 
                 diario = int4 = Diário 
                 pref = varchar(40) = prefeito 
                 vicepref = varchar(40) = vice prefeito 
                 fax = char(11) = fax 
                 cgc = char(14) = cgc 
                 cep = char(8) = cep 
                 tpropri = bool = Débitos proprietário 
                 tsocios = bool = Débitos Sócios 
                 prefeitura = bool = Prefeitura 
                 bairro = char(35) = Bairro 
                 numcgm = int4 = Número do CGM 
                 codtrib = char(4) = Órgão/Unidade da Instituição 
                 tribinst = int4 = Instituição SIAPC/PAD 
                 segmento = int4 = Segmento Código de Barras Febraban 
                 formvencfebraban = int4 = Forma do vencimento Febraban 
                 numero = int4 = Número do endereço 
                 nomedebconta = char(20) = Nome da instituição no débito em conta 
                 db21_tipoinstit = int4 = Tipo de Instituição 
                 db21_ativo = int4 = Ativo 
                 db21_regracgmiss = int4 = Regra CGM issbase 
                 db21_regracgmiptu = int4 = Regra Cgm Iptu 
                 db21_codcli = int4 = Código do cliente 
                 nomeinstabrev = varchar(20) = Nome da instituição para relatório 
                 db21_usasisagua = bool = Usa sistema de água 
                 db21_codigomunicipoestado = int4 = Código do município no estado 
                 db21_datalimite = date = Data limite que instituição é valida 
                 db21_criacao = date = Data de criação da instituição 
                 db21_compl = varchar(20) = Complemento do endereço 
                 email = varchar(200) = email 
                 db21_imgmarcadagua = oid = Marca D'agua Instituição 
                 db21_esfera = int4 = Esfera 
                 db21_tipopoder = int4 = Poder 
                 db21_codtj = int4 = Código do município na TJ 
                 ";
   //funcao construtor da classe 
   function cl_db_config() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_config"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->codigo = ($this->codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["codigo"]:$this->codigo);
       $this->nomeinst = ($this->nomeinst == ""?@$GLOBALS["HTTP_POST_VARS"]["nomeinst"]:$this->nomeinst);
       $this->ender = ($this->ender == ""?@$GLOBALS["HTTP_POST_VARS"]["ender"]:$this->ender);
       $this->munic = ($this->munic == ""?@$GLOBALS["HTTP_POST_VARS"]["munic"]:$this->munic);
       $this->uf = ($this->uf == ""?@$GLOBALS["HTTP_POST_VARS"]["uf"]:$this->uf);
       $this->telef = ($this->telef == ""?@$GLOBALS["HTTP_POST_VARS"]["telef"]:$this->telef);
       $this->ident = ($this->ident == ""?@$GLOBALS["HTTP_POST_VARS"]["ident"]:$this->ident);
       $this->tx_banc = ($this->tx_banc == ""?@$GLOBALS["HTTP_POST_VARS"]["tx_banc"]:$this->tx_banc);
       $this->numbanco = ($this->numbanco == ""?@$GLOBALS["HTTP_POST_VARS"]["numbanco"]:$this->numbanco);
       $this->url = ($this->url == ""?@$GLOBALS["HTTP_POST_VARS"]["url"]:$this->url);
       $this->logo = ($this->logo == ""?@$GLOBALS["HTTP_POST_VARS"]["logo"]:$this->logo);
       $this->figura = ($this->figura == ""?@$GLOBALS["HTTP_POST_VARS"]["figura"]:$this->figura);
       if($this->dtcont == ""){
         $this->dtcont_dia = ($this->dtcont_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtcont_dia"]:$this->dtcont_dia);
         $this->dtcont_mes = ($this->dtcont_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtcont_mes"]:$this->dtcont_mes);
         $this->dtcont_ano = ($this->dtcont_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtcont_ano"]:$this->dtcont_ano);
         if($this->dtcont_dia != ""){
            $this->dtcont = $this->dtcont_ano."-".$this->dtcont_mes."-".$this->dtcont_dia;
         }
       }
       $this->diario = ($this->diario == ""?@$GLOBALS["HTTP_POST_VARS"]["diario"]:$this->diario);
       $this->pref = ($this->pref == ""?@$GLOBALS["HTTP_POST_VARS"]["pref"]:$this->pref);
       $this->vicepref = ($this->vicepref == ""?@$GLOBALS["HTTP_POST_VARS"]["vicepref"]:$this->vicepref);
       $this->fax = ($this->fax == ""?@$GLOBALS["HTTP_POST_VARS"]["fax"]:$this->fax);
       $this->cgc = ($this->cgc == ""?@$GLOBALS["HTTP_POST_VARS"]["cgc"]:$this->cgc);
       $this->cep = ($this->cep == ""?@$GLOBALS["HTTP_POST_VARS"]["cep"]:$this->cep);
       $this->tpropri = ($this->tpropri == "f"?@$GLOBALS["HTTP_POST_VARS"]["tpropri"]:$this->tpropri);
       $this->tsocios = ($this->tsocios == "f"?@$GLOBALS["HTTP_POST_VARS"]["tsocios"]:$this->tsocios);
       $this->prefeitura = ($this->prefeitura == "f"?@$GLOBALS["HTTP_POST_VARS"]["prefeitura"]:$this->prefeitura);
       $this->bairro = ($this->bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["bairro"]:$this->bairro);
       $this->numcgm = ($this->numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["numcgm"]:$this->numcgm);
       $this->codtrib = ($this->codtrib == ""?@$GLOBALS["HTTP_POST_VARS"]["codtrib"]:$this->codtrib);
       $this->tribinst = ($this->tribinst == ""?@$GLOBALS["HTTP_POST_VARS"]["tribinst"]:$this->tribinst);
       $this->segmento = ($this->segmento == ""?@$GLOBALS["HTTP_POST_VARS"]["segmento"]:$this->segmento);
       $this->formvencfebraban = ($this->formvencfebraban == ""?@$GLOBALS["HTTP_POST_VARS"]["formvencfebraban"]:$this->formvencfebraban);
       $this->numero = ($this->numero == ""?@$GLOBALS["HTTP_POST_VARS"]["numero"]:$this->numero);
       $this->nomedebconta = ($this->nomedebconta == ""?@$GLOBALS["HTTP_POST_VARS"]["nomedebconta"]:$this->nomedebconta);
       $this->db21_tipoinstit = ($this->db21_tipoinstit == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_tipoinstit"]:$this->db21_tipoinstit);
       $this->db21_ativo = ($this->db21_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_ativo"]:$this->db21_ativo);
       $this->db21_regracgmiss = ($this->db21_regracgmiss == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_regracgmiss"]:$this->db21_regracgmiss);
       $this->db21_regracgmiptu = ($this->db21_regracgmiptu == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_regracgmiptu"]:$this->db21_regracgmiptu);
       $this->db21_codcli = ($this->db21_codcli == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_codcli"]:$this->db21_codcli);
       $this->nomeinstabrev = ($this->nomeinstabrev == ""?@$GLOBALS["HTTP_POST_VARS"]["nomeinstabrev"]:$this->nomeinstabrev);
       $this->db21_usasisagua = ($this->db21_usasisagua == "f"?@$GLOBALS["HTTP_POST_VARS"]["db21_usasisagua"]:$this->db21_usasisagua);
       $this->db21_codigomunicipoestado = ($this->db21_codigomunicipoestado == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_codigomunicipoestado"]:$this->db21_codigomunicipoestado);
       if($this->db21_datalimite == ""){
         $this->db21_datalimite_dia = ($this->db21_datalimite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_datalimite_dia"]:$this->db21_datalimite_dia);
         $this->db21_datalimite_mes = ($this->db21_datalimite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_datalimite_mes"]:$this->db21_datalimite_mes);
         $this->db21_datalimite_ano = ($this->db21_datalimite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_datalimite_ano"]:$this->db21_datalimite_ano);
         if($this->db21_datalimite_dia != ""){
            $this->db21_datalimite = $this->db21_datalimite_ano."-".$this->db21_datalimite_mes."-".$this->db21_datalimite_dia;
         }
       }
       if($this->db21_criacao == ""){
         $this->db21_criacao_dia = ($this->db21_criacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_criacao_dia"]:$this->db21_criacao_dia);
         $this->db21_criacao_mes = ($this->db21_criacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_criacao_mes"]:$this->db21_criacao_mes);
         $this->db21_criacao_ano = ($this->db21_criacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_criacao_ano"]:$this->db21_criacao_ano);
         if($this->db21_criacao_dia != ""){
            $this->db21_criacao = $this->db21_criacao_ano."-".$this->db21_criacao_mes."-".$this->db21_criacao_dia;
         }
       }
       $this->db21_compl = ($this->db21_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_compl"]:$this->db21_compl);
       $this->email = ($this->email == ""?@$GLOBALS["HTTP_POST_VARS"]["email"]:$this->email);
       $this->db21_imgmarcadagua = ($this->db21_imgmarcadagua == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_imgmarcadagua"]:$this->db21_imgmarcadagua);
       $this->db21_esfera = ($this->db21_esfera == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_esfera"]:$this->db21_esfera);
       $this->db21_tipopoder = ($this->db21_tipopoder == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_tipopoder"]:$this->db21_tipopoder);
       $this->db21_codtj = ($this->db21_codtj == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_codtj"]:$this->db21_codtj);
     }else{
       $this->codigo = ($this->codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["codigo"]:$this->codigo);
     }
   }
   // funcao para inclusao
   function incluir ($codigo){ 
      $this->atualizacampos();
     if($this->nomeinst == null ){ 
       $this->erro_sql = " Campo Nome da Instituição nao Informado.";
       $this->erro_campo = "nomeinst";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ender == null ){ 
       $this->erro_sql = " Campo endereço da instituição nao Informado.";
       $this->erro_campo = "ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->munic == null ){ 
       $this->erro_sql = " Campo Municipio da instituição nao Informado.";
       $this->erro_campo = "munic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->uf == null ){ 
       $this->erro_sql = " Campo Unidade Federativa da Instituição nao Informado.";
       $this->erro_campo = "uf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->telef == null ){ 
       $this->erro_sql = " Campo Telefone nao Informado.";
       $this->erro_campo = "telef";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ident == null ){ 
       $this->erro_sql = " Campo identidade nao Informado.";
       $this->erro_campo = "ident";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tx_banc == null ){ 
       $this->tx_banc = "0";
     }
     if($this->url == null ){ 
       $this->erro_sql = " Campo url nao Informado.";
       $this->erro_campo = "url";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtcont == null ){ 
       $this->erro_sql = " Campo data da contabilidade nao Informado.";
       $this->erro_campo = "dtcont_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->diario == null ){ 
       $this->erro_sql = " Campo Diário nao Informado.";
       $this->erro_campo = "diario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pref == null ){ 
       $this->erro_sql = " Campo prefeito nao Informado.";
       $this->erro_campo = "pref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vicepref == null ){ 
       $this->erro_sql = " Campo vice prefeito nao Informado.";
       $this->erro_campo = "vicepref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fax == null ){ 
       $this->erro_sql = " Campo fax nao Informado.";
       $this->erro_campo = "fax";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cgc == null ){ 
       $this->erro_sql = " Campo cgc nao Informado.";
       $this->erro_campo = "cgc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cep == null ){ 
       $this->erro_sql = " Campo cep nao Informado.";
       $this->erro_campo = "cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tpropri == null ){ 
       $this->erro_sql = " Campo Débitos proprietário nao Informado.";
       $this->erro_campo = "tpropri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tsocios == null ){ 
       $this->erro_sql = " Campo Débitos Sócios nao Informado.";
       $this->erro_campo = "tsocios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->prefeitura == null ){ 
       $this->erro_sql = " Campo Prefeitura nao Informado.";
       $this->erro_campo = "prefeitura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->numcgm == null ){ 
       $this->erro_sql = " Campo Número do CGM nao Informado.";
       $this->erro_campo = "numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->codtrib == null ){ 
       $this->erro_sql = " Campo Órgão/Unidade da Instituição nao Informado.";
       $this->erro_campo = "codtrib";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tribinst == null ){ 
       $this->erro_sql = " Campo Instituição SIAPC/PAD nao Informado.";
       $this->erro_campo = "tribinst";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->segmento == null ){ 
       $this->erro_sql = " Campo Segmento Código de Barras Febraban nao Informado.";
       $this->erro_campo = "segmento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->formvencfebraban == null ){ 
       $this->erro_sql = " Campo Forma do vencimento Febraban nao Informado.";
       $this->erro_campo = "formvencfebraban";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->numero == null ){ 
       $this->numero = "0";
     }
     if($this->nomedebconta == null ){ 
       $this->erro_sql = " Campo Nome da instituição no débito em conta nao Informado.";
       $this->erro_campo = "nomedebconta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db21_tipoinstit == null ){ 
       $this->erro_sql = " Campo Tipo de Instituição nao Informado.";
       $this->erro_campo = "db21_tipoinstit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db21_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "db21_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db21_regracgmiss == null ){ 
       $this->erro_sql = " Campo Regra CGM issbase nao Informado.";
       $this->erro_campo = "db21_regracgmiss";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db21_regracgmiptu == null ){ 
       $this->erro_sql = " Campo Regra Cgm Iptu nao Informado.";
       $this->erro_campo = "db21_regracgmiptu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db21_codcli == null ){ 
       $this->erro_sql = " Campo Código do cliente nao Informado.";
       $this->erro_campo = "db21_codcli";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->nomeinstabrev == null ){ 
       $this->erro_sql = " Campo Nome da instituição para relatório nao Informado.";
       $this->erro_campo = "nomeinstabrev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db21_usasisagua == null ){ 
       $this->erro_sql = " Campo Usa sistema de água nao Informado.";
       $this->erro_campo = "db21_usasisagua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db21_codigomunicipoestado == null ){ 
       $this->erro_sql = " Campo Código do município no estado nao Informado.";
       $this->erro_campo = "db21_codigomunicipoestado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db21_datalimite == null ){ 
       $this->db21_datalimite = "null";
     }
     if($this->db21_criacao == null ){ 
       $this->db21_criacao = "null";
     }
     if($this->db21_esfera == null ){ 
       $this->db21_esfera = "0";
     }
     if($this->db21_tipopoder == null ){ 
       $this->db21_tipopoder = "0";
     }
     if($this->db21_codtj == null ){ 
       $this->erro_sql = " Campo Código do município na TJ nao Informado.";
       $this->erro_campo = "db21_codtj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($codigo == "" || $codigo == null ){
       $result = db_query("select nextval('db_config_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_config_codigo_seq do campo: codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_config_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $codigo)){
         $this->erro_sql = " Campo codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->codigo = $codigo; 
       }
     }
     if(($this->codigo == null) || ($this->codigo == "") ){ 
       $this->erro_sql = " Campo codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_config(
                                       codigo 
                                      ,nomeinst 
                                      ,ender 
                                      ,munic 
                                      ,uf 
                                      ,telef 
                                      ,ident 
                                      ,tx_banc 
                                      ,numbanco 
                                      ,url 
                                      ,logo 
                                      ,figura 
                                      ,dtcont 
                                      ,diario 
                                      ,pref 
                                      ,vicepref 
                                      ,fax 
                                      ,cgc 
                                      ,cep 
                                      ,tpropri 
                                      ,tsocios 
                                      ,prefeitura 
                                      ,bairro 
                                      ,numcgm 
                                      ,codtrib 
                                      ,tribinst 
                                      ,segmento 
                                      ,formvencfebraban 
                                      ,numero 
                                      ,nomedebconta 
                                      ,db21_tipoinstit 
                                      ,db21_ativo 
                                      ,db21_regracgmiss 
                                      ,db21_regracgmiptu 
                                      ,db21_codcli 
                                      ,nomeinstabrev 
                                      ,db21_usasisagua 
                                      ,db21_codigomunicipoestado 
                                      ,db21_datalimite 
                                      ,db21_criacao 
                                      ,db21_compl 
                                      ,email 
                                      ,db21_imgmarcadagua 
                                      ,db21_esfera 
                                      ,db21_tipopoder 
                                      ,db21_codtj 
                       )
                values (
                                $this->codigo 
                               ,'$this->nomeinst' 
                               ,'$this->ender' 
                               ,'$this->munic' 
                               ,'$this->uf' 
                               ,'$this->telef' 
                               ,$this->ident 
                               ,$this->tx_banc 
                               ,'$this->numbanco' 
                               ,'$this->url' 
                               ,'$this->logo' 
                               ,'$this->figura' 
                               ,".($this->dtcont == "null" || $this->dtcont == ""?"null":"'".$this->dtcont."'")." 
                               ,$this->diario 
                               ,'$this->pref' 
                               ,'$this->vicepref' 
                               ,'$this->fax' 
                               ,'$this->cgc' 
                               ,'$this->cep' 
                               ,'$this->tpropri' 
                               ,'$this->tsocios' 
                               ,'$this->prefeitura' 
                               ,'$this->bairro' 
                               ,$this->numcgm 
                               ,'$this->codtrib' 
                               ,$this->tribinst 
                               ,$this->segmento 
                               ,$this->formvencfebraban 
                               ,$this->numero 
                               ,'$this->nomedebconta' 
                               ,$this->db21_tipoinstit 
                               ,$this->db21_ativo 
                               ,$this->db21_regracgmiss 
                               ,$this->db21_regracgmiptu 
                               ,$this->db21_codcli 
                               ,'$this->nomeinstabrev' 
                               ,'$this->db21_usasisagua' 
                               ,$this->db21_codigomunicipoestado 
                               ,".($this->db21_datalimite == "null" || $this->db21_datalimite == ""?"null":"'".$this->db21_datalimite."'")." 
                               ,".($this->db21_criacao == "null" || $this->db21_criacao == ""?"null":"'".$this->db21_criacao."'")." 
                               ,'$this->db21_compl' 
                               ,'$this->email' 
                               ,$this->db21_imgmarcadagua 
                               ,$this->db21_esfera 
                               ,$this->db21_tipopoder 
                               ,$this->db21_codtj 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,449,'$this->codigo','I')");
       $resac = db_query("insert into db_acount values($acount,83,449,'','".AddSlashes(pg_result($resaco,0,'codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,450,'','".AddSlashes(pg_result($resaco,0,'nomeinst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,451,'','".AddSlashes(pg_result($resaco,0,'ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,452,'','".AddSlashes(pg_result($resaco,0,'munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,453,'','".AddSlashes(pg_result($resaco,0,'uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,457,'','".AddSlashes(pg_result($resaco,0,'telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,459,'','".AddSlashes(pg_result($resaco,0,'ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,460,'','".AddSlashes(pg_result($resaco,0,'tx_banc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,461,'','".AddSlashes(pg_result($resaco,0,'numbanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,462,'','".AddSlashes(pg_result($resaco,0,'url'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,463,'','".AddSlashes(pg_result($resaco,0,'logo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,464,'','".AddSlashes(pg_result($resaco,0,'figura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,465,'','".AddSlashes(pg_result($resaco,0,'dtcont'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,466,'','".AddSlashes(pg_result($resaco,0,'diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,467,'','".AddSlashes(pg_result($resaco,0,'pref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,468,'','".AddSlashes(pg_result($resaco,0,'vicepref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,469,'','".AddSlashes(pg_result($resaco,0,'fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,470,'','".AddSlashes(pg_result($resaco,0,'cgc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,471,'','".AddSlashes(pg_result($resaco,0,'cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,3411,'','".AddSlashes(pg_result($resaco,0,'tpropri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,3412,'','".AddSlashes(pg_result($resaco,0,'tsocios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,3413,'','".AddSlashes(pg_result($resaco,0,'prefeitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,2323,'','".AddSlashes(pg_result($resaco,0,'bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,498,'','".AddSlashes(pg_result($resaco,0,'numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,8604,'','".AddSlashes(pg_result($resaco,0,'codtrib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,8605,'','".AddSlashes(pg_result($resaco,0,'tribinst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,8795,'','".AddSlashes(pg_result($resaco,0,'segmento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,8796,'','".AddSlashes(pg_result($resaco,0,'formvencfebraban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,6598,'','".AddSlashes(pg_result($resaco,0,'numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,8863,'','".AddSlashes(pg_result($resaco,0,'nomedebconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,8979,'','".AddSlashes(pg_result($resaco,0,'db21_tipoinstit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,9129,'','".AddSlashes(pg_result($resaco,0,'db21_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,9179,'','".AddSlashes(pg_result($resaco,0,'db21_regracgmiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,9178,'','".AddSlashes(pg_result($resaco,0,'db21_regracgmiptu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,9500,'','".AddSlashes(pg_result($resaco,0,'db21_codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,10178,'','".AddSlashes(pg_result($resaco,0,'nomeinstabrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,10967,'','".AddSlashes(pg_result($resaco,0,'db21_usasisagua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,15412,'','".AddSlashes(pg_result($resaco,0,'db21_codigomunicipoestado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,15416,'','".AddSlashes(pg_result($resaco,0,'db21_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,15414,'','".AddSlashes(pg_result($resaco,0,'db21_criacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,15413,'','".AddSlashes(pg_result($resaco,0,'db21_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,574,'','".AddSlashes(pg_result($resaco,0,'email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,17089,'','".AddSlashes(pg_result($resaco,0,'db21_imgmarcadagua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,17758,'','".AddSlashes(pg_result($resaco,0,'db21_esfera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,17759,'','".AddSlashes(pg_result($resaco,0,'db21_tipopoder'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,83,18161,'','".AddSlashes(pg_result($resaco,0,'db21_codtj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_config set ";
     $virgula = "";
     if(trim($this->codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codigo"])){ 
       $sql  .= $virgula." codigo = $this->codigo ";
       $virgula = ",";
       if(trim($this->codigo) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->nomeinst)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nomeinst"])){ 
       $sql  .= $virgula." nomeinst = '$this->nomeinst' ";
       $virgula = ",";
       if(trim($this->nomeinst) == null ){ 
         $this->erro_sql = " Campo Nome da Instituição nao Informado.";
         $this->erro_campo = "nomeinst";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ender"])){ 
       $sql  .= $virgula." ender = '$this->ender' ";
       $virgula = ",";
       if(trim($this->ender) == null ){ 
         $this->erro_sql = " Campo endereço da instituição nao Informado.";
         $this->erro_campo = "ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["munic"])){ 
       $sql  .= $virgula." munic = '$this->munic' ";
       $virgula = ",";
       if(trim($this->munic) == null ){ 
         $this->erro_sql = " Campo Municipio da instituição nao Informado.";
         $this->erro_campo = "munic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["uf"])){ 
       $sql  .= $virgula." uf = '$this->uf' ";
       $virgula = ",";
       if(trim($this->uf) == null ){ 
         $this->erro_sql = " Campo Unidade Federativa da Instituição nao Informado.";
         $this->erro_campo = "uf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->telef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["telef"])){ 
       $sql  .= $virgula." telef = '$this->telef' ";
       $virgula = ",";
       if(trim($this->telef) == null ){ 
         $this->erro_sql = " Campo Telefone nao Informado.";
         $this->erro_campo = "telef";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ident"])){ 
       $sql  .= $virgula." ident = $this->ident ";
       $virgula = ",";
       if(trim($this->ident) == null ){ 
         $this->erro_sql = " Campo identidade nao Informado.";
         $this->erro_campo = "ident";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tx_banc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tx_banc"])){ 
        if(trim($this->tx_banc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["tx_banc"])){ 
           $this->tx_banc = "0" ; 
        } 
       $sql  .= $virgula." tx_banc = $this->tx_banc ";
       $virgula = ",";
     }
     if(trim($this->numbanco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["numbanco"])){ 
       $sql  .= $virgula." numbanco = '$this->numbanco' ";
       $virgula = ",";
     }
     if(trim($this->url)!="" || isset($GLOBALS["HTTP_POST_VARS"]["url"])){ 
       $sql  .= $virgula." url = '$this->url' ";
       $virgula = ",";
       if(trim($this->url) == null ){ 
         $this->erro_sql = " Campo url nao Informado.";
         $this->erro_campo = "url";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->logo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["logo"])){ 
       $sql  .= $virgula." logo = '$this->logo' ";
       $virgula = ",";
     }
     if(trim($this->figura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["figura"])){ 
       $sql  .= $virgula." figura = '$this->figura' ";
       $virgula = ",";
     }
     if(trim($this->dtcont)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtcont_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtcont_dia"] !="") ){ 
       $sql  .= $virgula." dtcont = '$this->dtcont' ";
       $virgula = ",";
       if(trim($this->dtcont) == null ){ 
         $this->erro_sql = " Campo data da contabilidade nao Informado.";
         $this->erro_campo = "dtcont_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtcont_dia"])){ 
         $sql  .= $virgula." dtcont = null ";
         $virgula = ",";
         if(trim($this->dtcont) == null ){ 
           $this->erro_sql = " Campo data da contabilidade nao Informado.";
           $this->erro_campo = "dtcont_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->diario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["diario"])){ 
       $sql  .= $virgula." diario = $this->diario ";
       $virgula = ",";
       if(trim($this->diario) == null ){ 
         $this->erro_sql = " Campo Diário nao Informado.";
         $this->erro_campo = "diario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pref"])){ 
       $sql  .= $virgula." pref = '$this->pref' ";
       $virgula = ",";
       if(trim($this->pref) == null ){ 
         $this->erro_sql = " Campo prefeito nao Informado.";
         $this->erro_campo = "pref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vicepref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vicepref"])){ 
       $sql  .= $virgula." vicepref = '$this->vicepref' ";
       $virgula = ",";
       if(trim($this->vicepref) == null ){ 
         $this->erro_sql = " Campo vice prefeito nao Informado.";
         $this->erro_campo = "vicepref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fax"])){ 
       $sql  .= $virgula." fax = '$this->fax' ";
       $virgula = ",";
       if(trim($this->fax) == null ){ 
         $this->erro_sql = " Campo fax nao Informado.";
         $this->erro_campo = "fax";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cgc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgc"])){ 
       $sql  .= $virgula." cgc = '$this->cgc' ";
       $virgula = ",";
       if(trim($this->cgc) == null ){ 
         $this->erro_sql = " Campo cgc nao Informado.";
         $this->erro_campo = "cgc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cep"])){ 
       $sql  .= $virgula." cep = '$this->cep' ";
       $virgula = ",";
       if(trim($this->cep) == null ){ 
         $this->erro_sql = " Campo cep nao Informado.";
         $this->erro_campo = "cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tpropri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tpropri"])){ 
       $sql  .= $virgula." tpropri = '$this->tpropri' ";
       $virgula = ",";
       if(trim($this->tpropri) == null ){ 
         $this->erro_sql = " Campo Débitos proprietário nao Informado.";
         $this->erro_campo = "tpropri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tsocios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tsocios"])){ 
       $sql  .= $virgula." tsocios = '$this->tsocios' ";
       $virgula = ",";
       if(trim($this->tsocios) == null ){ 
         $this->erro_sql = " Campo Débitos Sócios nao Informado.";
         $this->erro_campo = "tsocios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->prefeitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["prefeitura"])){ 
       $sql  .= $virgula." prefeitura = '$this->prefeitura' ";
       $virgula = ",";
       if(trim($this->prefeitura) == null ){ 
         $this->erro_sql = " Campo Prefeitura nao Informado.";
         $this->erro_campo = "prefeitura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bairro"])){ 
       $sql  .= $virgula." bairro = '$this->bairro' ";
       $virgula = ",";
     }
     if(trim($this->numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["numcgm"])){ 
       $sql  .= $virgula." numcgm = $this->numcgm ";
       $virgula = ",";
       if(trim($this->numcgm) == null ){ 
         $this->erro_sql = " Campo Número do CGM nao Informado.";
         $this->erro_campo = "numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codtrib)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codtrib"])){ 
       $sql  .= $virgula." codtrib = '$this->codtrib' ";
       $virgula = ",";
       if(trim($this->codtrib) == null ){ 
         $this->erro_sql = " Campo Órgão/Unidade da Instituição nao Informado.";
         $this->erro_campo = "codtrib";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tribinst)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tribinst"])){ 
       $sql  .= $virgula." tribinst = $this->tribinst ";
       $virgula = ",";
       if(trim($this->tribinst) == null ){ 
         $this->erro_sql = " Campo Instituição SIAPC/PAD nao Informado.";
         $this->erro_campo = "tribinst";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->segmento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["segmento"])){ 
       $sql  .= $virgula." segmento = $this->segmento ";
       $virgula = ",";
       if(trim($this->segmento) == null ){ 
         $this->erro_sql = " Campo Segmento Código de Barras Febraban nao Informado.";
         $this->erro_campo = "segmento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->formvencfebraban)!="" || isset($GLOBALS["HTTP_POST_VARS"]["formvencfebraban"])){ 
       $sql  .= $virgula." formvencfebraban = $this->formvencfebraban ";
       $virgula = ",";
       if(trim($this->formvencfebraban) == null ){ 
         $this->erro_sql = " Campo Forma do vencimento Febraban nao Informado.";
         $this->erro_campo = "formvencfebraban";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["numero"])){ 
        if(trim($this->numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["numero"])){ 
           $this->numero = "0" ; 
        } 
       $sql  .= $virgula." numero = $this->numero ";
       $virgula = ",";
     }
     if(trim($this->nomedebconta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nomedebconta"])){ 
       $sql  .= $virgula." nomedebconta = '$this->nomedebconta' ";
       $virgula = ",";
       if(trim($this->nomedebconta) == null ){ 
         $this->erro_sql = " Campo Nome da instituição no débito em conta nao Informado.";
         $this->erro_campo = "nomedebconta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db21_tipoinstit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_tipoinstit"])){ 
       $sql  .= $virgula." db21_tipoinstit = $this->db21_tipoinstit ";
       $virgula = ",";
       if(trim($this->db21_tipoinstit) == null ){ 
         $this->erro_sql = " Campo Tipo de Instituição nao Informado.";
         $this->erro_campo = "db21_tipoinstit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db21_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_ativo"])){ 
       $sql  .= $virgula." db21_ativo = $this->db21_ativo ";
       $virgula = ",";
       if(trim($this->db21_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "db21_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db21_regracgmiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_regracgmiss"])){ 
       $sql  .= $virgula." db21_regracgmiss = $this->db21_regracgmiss ";
       $virgula = ",";
       if(trim($this->db21_regracgmiss) == null ){ 
         $this->erro_sql = " Campo Regra CGM issbase nao Informado.";
         $this->erro_campo = "db21_regracgmiss";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db21_regracgmiptu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_regracgmiptu"])){ 
       $sql  .= $virgula." db21_regracgmiptu = $this->db21_regracgmiptu ";
       $virgula = ",";
       if(trim($this->db21_regracgmiptu) == null ){ 
         $this->erro_sql = " Campo Regra Cgm Iptu nao Informado.";
         $this->erro_campo = "db21_regracgmiptu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db21_codcli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_codcli"])){ 
       $sql  .= $virgula." db21_codcli = $this->db21_codcli ";
       $virgula = ",";
       if(trim($this->db21_codcli) == null ){ 
         $this->erro_sql = " Campo Código do cliente nao Informado.";
         $this->erro_campo = "db21_codcli";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->nomeinstabrev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nomeinstabrev"])){ 
       $sql  .= $virgula." nomeinstabrev = '$this->nomeinstabrev' ";
       $virgula = ",";
       if(trim($this->nomeinstabrev) == null ){ 
         $this->erro_sql = " Campo Nome da instituição para relatório nao Informado.";
         $this->erro_campo = "nomeinstabrev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db21_usasisagua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_usasisagua"])){ 
       $sql  .= $virgula." db21_usasisagua = '$this->db21_usasisagua' ";
       $virgula = ",";
       if(trim($this->db21_usasisagua) == null ){ 
         $this->erro_sql = " Campo Usa sistema de água nao Informado.";
         $this->erro_campo = "db21_usasisagua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db21_codigomunicipoestado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_codigomunicipoestado"])){ 
       $sql  .= $virgula." db21_codigomunicipoestado = $this->db21_codigomunicipoestado ";
       $virgula = ",";
       if(trim($this->db21_codigomunicipoestado) == null ){ 
         $this->erro_sql = " Campo Código do município no estado nao Informado.";
         $this->erro_campo = "db21_codigomunicipoestado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db21_datalimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_datalimite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db21_datalimite_dia"] !="") ){ 
       $sql  .= $virgula." db21_datalimite = '$this->db21_datalimite' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db21_datalimite_dia"])){ 
         $sql  .= $virgula." db21_datalimite = null ";
         $virgula = ",";
       }
     }
     if(trim($this->db21_criacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_criacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db21_criacao_dia"] !="") ){ 
       $sql  .= $virgula." db21_criacao = '$this->db21_criacao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db21_criacao_dia"])){ 
         $sql  .= $virgula." db21_criacao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->db21_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_compl"])){ 
       $sql  .= $virgula." db21_compl = '$this->db21_compl' ";
       $virgula = ",";
     }
     if(trim($this->email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["email"])){ 
       $sql  .= $virgula." email = '$this->email' ";
       $virgula = ",";
     }
     if(trim($this->db21_imgmarcadagua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_imgmarcadagua"])){ 
       $sql  .= $virgula." db21_imgmarcadagua = $this->db21_imgmarcadagua ";
       $virgula = ",";
     }
     if(trim($this->db21_esfera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_esfera"])){ 
        if(trim($this->db21_esfera)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db21_esfera"])){ 
           $this->db21_esfera = "0" ; 
        } 
       $sql  .= $virgula." db21_esfera = $this->db21_esfera ";
       $virgula = ",";
     }
     if(trim($this->db21_tipopoder)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_tipopoder"])){ 
        if(trim($this->db21_tipopoder)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db21_tipopoder"])){ 
           $this->db21_tipopoder = "0" ; 
        } 
       $sql  .= $virgula." db21_tipopoder = $this->db21_tipopoder ";
       $virgula = ",";
     }
     if(trim($this->db21_codtj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_codtj"])){ 
       $sql  .= $virgula." db21_codtj = $this->db21_codtj ";
       $virgula = ",";
       if(trim($this->db21_codtj) == null ){ 
         $this->erro_sql = " Campo Código do município na TJ nao Informado.";
         $this->erro_campo = "db21_codtj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($codigo!=null){
       $sql .= " codigo = $this->codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,449,'$this->codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codigo"]) || $this->codigo != "")
           $resac = db_query("insert into db_acount values($acount,83,449,'".AddSlashes(pg_result($resaco,$conresaco,'codigo'))."','$this->codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nomeinst"]) || $this->nomeinst != "")
           $resac = db_query("insert into db_acount values($acount,83,450,'".AddSlashes(pg_result($resaco,$conresaco,'nomeinst'))."','$this->nomeinst',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ender"]) || $this->ender != "")
           $resac = db_query("insert into db_acount values($acount,83,451,'".AddSlashes(pg_result($resaco,$conresaco,'ender'))."','$this->ender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["munic"]) || $this->munic != "")
           $resac = db_query("insert into db_acount values($acount,83,452,'".AddSlashes(pg_result($resaco,$conresaco,'munic'))."','$this->munic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["uf"]) || $this->uf != "")
           $resac = db_query("insert into db_acount values($acount,83,453,'".AddSlashes(pg_result($resaco,$conresaco,'uf'))."','$this->uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["telef"]) || $this->telef != "")
           $resac = db_query("insert into db_acount values($acount,83,457,'".AddSlashes(pg_result($resaco,$conresaco,'telef'))."','$this->telef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ident"]) || $this->ident != "")
           $resac = db_query("insert into db_acount values($acount,83,459,'".AddSlashes(pg_result($resaco,$conresaco,'ident'))."','$this->ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tx_banc"]) || $this->tx_banc != "")
           $resac = db_query("insert into db_acount values($acount,83,460,'".AddSlashes(pg_result($resaco,$conresaco,'tx_banc'))."','$this->tx_banc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["numbanco"]) || $this->numbanco != "")
           $resac = db_query("insert into db_acount values($acount,83,461,'".AddSlashes(pg_result($resaco,$conresaco,'numbanco'))."','$this->numbanco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["url"]) || $this->url != "")
           $resac = db_query("insert into db_acount values($acount,83,462,'".AddSlashes(pg_result($resaco,$conresaco,'url'))."','$this->url',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["logo"]) || $this->logo != "")
           $resac = db_query("insert into db_acount values($acount,83,463,'".AddSlashes(pg_result($resaco,$conresaco,'logo'))."','$this->logo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["figura"]) || $this->figura != "")
           $resac = db_query("insert into db_acount values($acount,83,464,'".AddSlashes(pg_result($resaco,$conresaco,'figura'))."','$this->figura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtcont"]) || $this->dtcont != "")
           $resac = db_query("insert into db_acount values($acount,83,465,'".AddSlashes(pg_result($resaco,$conresaco,'dtcont'))."','$this->dtcont',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["diario"]) || $this->diario != "")
           $resac = db_query("insert into db_acount values($acount,83,466,'".AddSlashes(pg_result($resaco,$conresaco,'diario'))."','$this->diario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pref"]) || $this->pref != "")
           $resac = db_query("insert into db_acount values($acount,83,467,'".AddSlashes(pg_result($resaco,$conresaco,'pref'))."','$this->pref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vicepref"]) || $this->vicepref != "")
           $resac = db_query("insert into db_acount values($acount,83,468,'".AddSlashes(pg_result($resaco,$conresaco,'vicepref'))."','$this->vicepref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fax"]) || $this->fax != "")
           $resac = db_query("insert into db_acount values($acount,83,469,'".AddSlashes(pg_result($resaco,$conresaco,'fax'))."','$this->fax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cgc"]) || $this->cgc != "")
           $resac = db_query("insert into db_acount values($acount,83,470,'".AddSlashes(pg_result($resaco,$conresaco,'cgc'))."','$this->cgc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cep"]) || $this->cep != "")
           $resac = db_query("insert into db_acount values($acount,83,471,'".AddSlashes(pg_result($resaco,$conresaco,'cep'))."','$this->cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tpropri"]) || $this->tpropri != "")
           $resac = db_query("insert into db_acount values($acount,83,3411,'".AddSlashes(pg_result($resaco,$conresaco,'tpropri'))."','$this->tpropri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tsocios"]) || $this->tsocios != "")
           $resac = db_query("insert into db_acount values($acount,83,3412,'".AddSlashes(pg_result($resaco,$conresaco,'tsocios'))."','$this->tsocios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["prefeitura"]) || $this->prefeitura != "")
           $resac = db_query("insert into db_acount values($acount,83,3413,'".AddSlashes(pg_result($resaco,$conresaco,'prefeitura'))."','$this->prefeitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bairro"]) || $this->bairro != "")
           $resac = db_query("insert into db_acount values($acount,83,2323,'".AddSlashes(pg_result($resaco,$conresaco,'bairro'))."','$this->bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["numcgm"]) || $this->numcgm != "")
           $resac = db_query("insert into db_acount values($acount,83,498,'".AddSlashes(pg_result($resaco,$conresaco,'numcgm'))."','$this->numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codtrib"]) || $this->codtrib != "")
           $resac = db_query("insert into db_acount values($acount,83,8604,'".AddSlashes(pg_result($resaco,$conresaco,'codtrib'))."','$this->codtrib',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tribinst"]) || $this->tribinst != "")
           $resac = db_query("insert into db_acount values($acount,83,8605,'".AddSlashes(pg_result($resaco,$conresaco,'tribinst'))."','$this->tribinst',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["segmento"]) || $this->segmento != "")
           $resac = db_query("insert into db_acount values($acount,83,8795,'".AddSlashes(pg_result($resaco,$conresaco,'segmento'))."','$this->segmento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["formvencfebraban"]) || $this->formvencfebraban != "")
           $resac = db_query("insert into db_acount values($acount,83,8796,'".AddSlashes(pg_result($resaco,$conresaco,'formvencfebraban'))."','$this->formvencfebraban',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["numero"]) || $this->numero != "")
           $resac = db_query("insert into db_acount values($acount,83,6598,'".AddSlashes(pg_result($resaco,$conresaco,'numero'))."','$this->numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nomedebconta"]) || $this->nomedebconta != "")
           $resac = db_query("insert into db_acount values($acount,83,8863,'".AddSlashes(pg_result($resaco,$conresaco,'nomedebconta'))."','$this->nomedebconta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_tipoinstit"]) || $this->db21_tipoinstit != "")
           $resac = db_query("insert into db_acount values($acount,83,8979,'".AddSlashes(pg_result($resaco,$conresaco,'db21_tipoinstit'))."','$this->db21_tipoinstit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_ativo"]) || $this->db21_ativo != "")
           $resac = db_query("insert into db_acount values($acount,83,9129,'".AddSlashes(pg_result($resaco,$conresaco,'db21_ativo'))."','$this->db21_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_regracgmiss"]) || $this->db21_regracgmiss != "")
           $resac = db_query("insert into db_acount values($acount,83,9179,'".AddSlashes(pg_result($resaco,$conresaco,'db21_regracgmiss'))."','$this->db21_regracgmiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_regracgmiptu"]) || $this->db21_regracgmiptu != "")
           $resac = db_query("insert into db_acount values($acount,83,9178,'".AddSlashes(pg_result($resaco,$conresaco,'db21_regracgmiptu'))."','$this->db21_regracgmiptu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_codcli"]) || $this->db21_codcli != "")
           $resac = db_query("insert into db_acount values($acount,83,9500,'".AddSlashes(pg_result($resaco,$conresaco,'db21_codcli'))."','$this->db21_codcli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nomeinstabrev"]) || $this->nomeinstabrev != "")
           $resac = db_query("insert into db_acount values($acount,83,10178,'".AddSlashes(pg_result($resaco,$conresaco,'nomeinstabrev'))."','$this->nomeinstabrev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_usasisagua"]) || $this->db21_usasisagua != "")
           $resac = db_query("insert into db_acount values($acount,83,10967,'".AddSlashes(pg_result($resaco,$conresaco,'db21_usasisagua'))."','$this->db21_usasisagua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_codigomunicipoestado"]) || $this->db21_codigomunicipoestado != "")
           $resac = db_query("insert into db_acount values($acount,83,15412,'".AddSlashes(pg_result($resaco,$conresaco,'db21_codigomunicipoestado'))."','$this->db21_codigomunicipoestado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_datalimite"]) || $this->db21_datalimite != "")
           $resac = db_query("insert into db_acount values($acount,83,15416,'".AddSlashes(pg_result($resaco,$conresaco,'db21_datalimite'))."','$this->db21_datalimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_criacao"]) || $this->db21_criacao != "")
           $resac = db_query("insert into db_acount values($acount,83,15414,'".AddSlashes(pg_result($resaco,$conresaco,'db21_criacao'))."','$this->db21_criacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_compl"]) || $this->db21_compl != "")
           $resac = db_query("insert into db_acount values($acount,83,15413,'".AddSlashes(pg_result($resaco,$conresaco,'db21_compl'))."','$this->db21_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["email"]) || $this->email != "")
           $resac = db_query("insert into db_acount values($acount,83,574,'".AddSlashes(pg_result($resaco,$conresaco,'email'))."','$this->email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_imgmarcadagua"]) || $this->db21_imgmarcadagua != "")
           $resac = db_query("insert into db_acount values($acount,83,17089,'".AddSlashes(pg_result($resaco,$conresaco,'db21_imgmarcadagua'))."','$this->db21_imgmarcadagua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_esfera"]) || $this->db21_esfera != "")
           $resac = db_query("insert into db_acount values($acount,83,17758,'".AddSlashes(pg_result($resaco,$conresaco,'db21_esfera'))."','$this->db21_esfera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_tipopoder"]) || $this->db21_tipopoder != "")
           $resac = db_query("insert into db_acount values($acount,83,17759,'".AddSlashes(pg_result($resaco,$conresaco,'db21_tipopoder'))."','$this->db21_tipopoder',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_codtj"]) || $this->db21_codtj != "")
           $resac = db_query("insert into db_acount values($acount,83,18161,'".AddSlashes(pg_result($resaco,$conresaco,'db21_codtj'))."','$this->db21_codtj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,449,'$codigo','E')");
         $resac = db_query("insert into db_acount values($acount,83,449,'','".AddSlashes(pg_result($resaco,$iresaco,'codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,450,'','".AddSlashes(pg_result($resaco,$iresaco,'nomeinst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,451,'','".AddSlashes(pg_result($resaco,$iresaco,'ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,452,'','".AddSlashes(pg_result($resaco,$iresaco,'munic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,453,'','".AddSlashes(pg_result($resaco,$iresaco,'uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,457,'','".AddSlashes(pg_result($resaco,$iresaco,'telef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,459,'','".AddSlashes(pg_result($resaco,$iresaco,'ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,460,'','".AddSlashes(pg_result($resaco,$iresaco,'tx_banc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,461,'','".AddSlashes(pg_result($resaco,$iresaco,'numbanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,462,'','".AddSlashes(pg_result($resaco,$iresaco,'url'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,463,'','".AddSlashes(pg_result($resaco,$iresaco,'logo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,464,'','".AddSlashes(pg_result($resaco,$iresaco,'figura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,465,'','".AddSlashes(pg_result($resaco,$iresaco,'dtcont'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,466,'','".AddSlashes(pg_result($resaco,$iresaco,'diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,467,'','".AddSlashes(pg_result($resaco,$iresaco,'pref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,468,'','".AddSlashes(pg_result($resaco,$iresaco,'vicepref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,469,'','".AddSlashes(pg_result($resaco,$iresaco,'fax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,470,'','".AddSlashes(pg_result($resaco,$iresaco,'cgc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,471,'','".AddSlashes(pg_result($resaco,$iresaco,'cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,3411,'','".AddSlashes(pg_result($resaco,$iresaco,'tpropri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,3412,'','".AddSlashes(pg_result($resaco,$iresaco,'tsocios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,3413,'','".AddSlashes(pg_result($resaco,$iresaco,'prefeitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,2323,'','".AddSlashes(pg_result($resaco,$iresaco,'bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,498,'','".AddSlashes(pg_result($resaco,$iresaco,'numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,8604,'','".AddSlashes(pg_result($resaco,$iresaco,'codtrib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,8605,'','".AddSlashes(pg_result($resaco,$iresaco,'tribinst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,8795,'','".AddSlashes(pg_result($resaco,$iresaco,'segmento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,8796,'','".AddSlashes(pg_result($resaco,$iresaco,'formvencfebraban'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,6598,'','".AddSlashes(pg_result($resaco,$iresaco,'numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,8863,'','".AddSlashes(pg_result($resaco,$iresaco,'nomedebconta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,8979,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_tipoinstit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,9129,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,9179,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_regracgmiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,9178,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_regracgmiptu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,9500,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,10178,'','".AddSlashes(pg_result($resaco,$iresaco,'nomeinstabrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,10967,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_usasisagua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,15412,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_codigomunicipoestado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,15416,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,15414,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_criacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,15413,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,574,'','".AddSlashes(pg_result($resaco,$iresaco,'email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,17089,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_imgmarcadagua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,17758,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_esfera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,17759,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_tipopoder'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,83,18161,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_codtj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_config
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codigo = $codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:db_config";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_config ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($codigo!=null ){
         $sql2 .= " where db_config.codigo = $codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_config ";
     $sql2 = "";
     if($dbwhere==""){
       if($codigo!=null ){
         $sql2 .= " where db_config.codigo = $codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_log ( $codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_config ";
     $sql2 = "inner join ceplocalidades on cp05_localidades = munic
              inner join ceplogradouros on cp06_codlocalidade = cp05_codlocalidades";
     if($dbwhere==""){
       if($codigo!=null ){
         $sql2 .= " where db_config.codigo = $codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_usu ( $codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_config ";
     $sql .= " inner join db_userinst on id_instit = db_config.codigo ";
     if($dbwhere==""){
       if($codigo!=null ){
         $sql2 .= " where db_config.codigo = $codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_tipoinstit ( $codigo=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_config ";
     $sql2 ="inner join db_tipoinstit on db21_codtipo=db21_tipoinstit";
     if($dbwhere==""){
       if($codigo!=null ){
         $sql2 .= " where db_config.codigo = $codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;

}
   function sql_query_siafi( $codigo=null,$campos="*",$ordem=null,$dbwhere=""){
   	
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_config ";
     $sql2 ="       inner join municipiosiafi on municipiosiafi.q110_cnpj = db_config.cgc";
     if($dbwhere==""){
       if($codigo!=null ){
         $sql2 .= " where db_config.codigo = $codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;

  }

  /**
   * Retorna um objeto com os dados da instituição
   * @param integer $iInstit - Instituição qual os dados devem ser retornados
   * @return mixed boolean, object db_fields
   */
  function getParametrosInstituicao($iInstit=null) {
  
    if (empty($iInstit)){
      $iInstit = db_getsession("DB_instit");
    }
    
  	$sSql = "select * from db_config where codigo = " . $iInstit;
  
  	$rsSql = db_query($sSql);
  
  	if  ( $rsSql && pg_num_rows($rsSql) ) {
  		return db_utils::fieldsMemory($rsSql, 0);
  	}
  	return false;
  }


  function getCodigoTom($iInstit = null) {

    if (empty($iInstit)){
      $iInstit = db_getsession("DB_instit");
    }

    $sSql  = "select db125_codigosistema                                                                  ";
    $sSql .= "  from db_config                                                                            ";
    $sSql .= "       inner join cadenderestado           on trim(db71_sigla)        = uf                  ";
    $sSql .= "       inner join cadendermunicipio        on db71_sequencial         = db72_cadenderestado ";
    $sSql .= "                                          and trim(db72_descricao)    = munic               ";
    $sSql .= "       inner join cadendermunicipiosistema on db125_cadendermunicipio = db72_sequencial     ";
    $sSql .= "                                          and db125_db_sistemaexterno = 5                   ";
    $sSql .= " where codigo = $iInstit;                                                                   ";

    $rsDbConfig = $this->sql_record($sSql);

    if ($this->numrows > 0) {
      return db_utils::fieldsMemory($rsDbConfig, 0);
    }

    return null;
  }
}
?>