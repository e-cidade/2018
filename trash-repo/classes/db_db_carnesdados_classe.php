<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE db_carnesdados
class cl_db_carnesdados { 
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
   var $idcampos = 0; 
   var $codmodelo = 0; 
   var $z01_nome = null; 
   var $z01_ender = null; 
   var $z01_munic = null; 
   var $z01_cep = null; 
   var $z01_uf = null; 
   var $z01_bairro = null; 
   var $k00_codbco = 0; 
   var $k00_codage = null; 
   var $k00_txban = 0; 
   var $k00_rectx = 0; 
   var $j34_setor = null; 
   var $j34_quadra = null; 
   var $j34_lote = null; 
   var $j39_numero = 0; 
   var $j39_compl = null; 
   var $j01_matric = 0; 
   var $q02_inscr = 0; 
   var $k15_local = null; 
   var $k15_aceite = null; 
   var $k15_carte = null; 
   var $k15_espec = null; 
   var $k15_gerent = null; 
   var $k15_ageced = null; 
   var $k00_hist1 = null; 
   var $k00_hist2 = null; 
   var $k00_hist3 = null; 
   var $k00_hist4 = null; 
   var $k00_hist5 = null; 
   var $k00_hist6 = null; 
   var $k00_hist7 = null; 
   var $k00_hist8 = null; 
   var $linhadigitavel = null; 
   var $codigobarras = null; 
   var $vlrtotal = 0; 
   var $vencimento_dia = null; 
   var $vencimento_mes = null; 
   var $vencimento_ano = null; 
   var $vencimento = null; 
   var $ip = null; 
   var $numpre = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 idcampos = int4 = Id Campos 
                 codmodelo = int4 = Código Modelo 
                 z01_nome = varchar(40) = Nome/Razão Social 
                 z01_ender = varchar(100) = Endereço 
                 z01_munic = varchar(40) = Município 
                 z01_cep = varchar(8) = CEP 
                 z01_uf = varchar(2) = UF 
                 z01_bairro = varchar(40) = Bairro 
                 k00_codbco = int4 = codigo do banco 
                 k00_codage = char(5) = codigo da agencia 
                 k00_txban = float8 = Taxa Bancária 
                 k00_rectx = int4 = Receita da Taxa Bancaria 
                 j34_setor = char(4) = Setor 
                 j34_quadra = char(4) = Quadra 
                 j34_lote = char(4) = Lote 
                 j39_numero = int4 = Número 
                 j39_compl = varchar(20) = Complemento 
                 j01_matric = int4 = Matrícula do Imóvel 
                 q02_inscr = int4 = Inscrição Municipal 
                 k15_local = char(40) = local 
                 k15_aceite = char(10) = aceite 
                 k15_carte = char(2) = carteira 
                 k15_espec = char(20) = especie do documento 
                 k15_gerent = char(30) = gerente 
                 k15_ageced = char(30) = agencia do cedente 
                 k00_hist1 = varchar(80) = historico do recibo 1 
                 k00_hist2 = varchar(80) = historico do recibo 2 
                 k00_hist3 = varchar(80) = historico do recibo 3 
                 k00_hist4 = varchar(80) = historico do recibo 4 
                 k00_hist5 = varchar(80) = historico do recibo 5 
                 k00_hist6 = varchar(80) = historico do recibo 6 
                 k00_hist7 = varchar(80) = historico do recibo 7 
                 k00_hist8 = varchar(80) = historico do recibo 8 
                 linhadigitavel = varchar(100) = Linha Digitavel 
                 codigobarras = varchar(100) = Código Barras 
                 vlrtotal = float8 = Valor Total 
                 vencimento = date = Data vencimento 
                 ip = varchar(50) = IP 
                 numpre = char(15) = Nº arrecadação 
                 ";
   //funcao construtor da classe 
   function cl_db_carnesdados() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_carnesdados"); 
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
       $this->idcampos = ($this->idcampos == ""?@$GLOBALS["HTTP_POST_VARS"]["idcampos"]:$this->idcampos);
       $this->codmodelo = ($this->codmodelo == ""?@$GLOBALS["HTTP_POST_VARS"]["codmodelo"]:$this->codmodelo);
       $this->z01_nome = ($this->z01_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_nome"]:$this->z01_nome);
       $this->z01_ender = ($this->z01_ender == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_ender"]:$this->z01_ender);
       $this->z01_munic = ($this->z01_munic == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_munic"]:$this->z01_munic);
       $this->z01_cep = ($this->z01_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cep"]:$this->z01_cep);
       $this->z01_uf = ($this->z01_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_uf"]:$this->z01_uf);
       $this->z01_bairro = ($this->z01_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_bairro"]:$this->z01_bairro);
       $this->k00_codbco = ($this->k00_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_codbco"]:$this->k00_codbco);
       $this->k00_codage = ($this->k00_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_codage"]:$this->k00_codage);
       $this->k00_txban = ($this->k00_txban == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_txban"]:$this->k00_txban);
       $this->k00_rectx = ($this->k00_rectx == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_rectx"]:$this->k00_rectx);
       $this->j34_setor = ($this->j34_setor == ""?@$GLOBALS["HTTP_POST_VARS"]["j34_setor"]:$this->j34_setor);
       $this->j34_quadra = ($this->j34_quadra == ""?@$GLOBALS["HTTP_POST_VARS"]["j34_quadra"]:$this->j34_quadra);
       $this->j34_lote = ($this->j34_lote == ""?@$GLOBALS["HTTP_POST_VARS"]["j34_lote"]:$this->j34_lote);
       $this->j39_numero = ($this->j39_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_numero"]:$this->j39_numero);
       $this->j39_compl = ($this->j39_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["j39_compl"]:$this->j39_compl);
       $this->j01_matric = ($this->j01_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j01_matric"]:$this->j01_matric);
       $this->q02_inscr = ($this->q02_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q02_inscr"]:$this->q02_inscr);
       $this->k15_local = ($this->k15_local == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_local"]:$this->k15_local);
       $this->k15_aceite = ($this->k15_aceite == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_aceite"]:$this->k15_aceite);
       $this->k15_carte = ($this->k15_carte == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_carte"]:$this->k15_carte);
       $this->k15_espec = ($this->k15_espec == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_espec"]:$this->k15_espec);
       $this->k15_gerent = ($this->k15_gerent == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_gerent"]:$this->k15_gerent);
       $this->k15_ageced = ($this->k15_ageced == ""?@$GLOBALS["HTTP_POST_VARS"]["k15_ageced"]:$this->k15_ageced);
       $this->k00_hist1 = ($this->k00_hist1 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist1"]:$this->k00_hist1);
       $this->k00_hist2 = ($this->k00_hist2 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist2"]:$this->k00_hist2);
       $this->k00_hist3 = ($this->k00_hist3 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist3"]:$this->k00_hist3);
       $this->k00_hist4 = ($this->k00_hist4 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist4"]:$this->k00_hist4);
       $this->k00_hist5 = ($this->k00_hist5 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist5"]:$this->k00_hist5);
       $this->k00_hist6 = ($this->k00_hist6 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist6"]:$this->k00_hist6);
       $this->k00_hist7 = ($this->k00_hist7 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist7"]:$this->k00_hist7);
       $this->k00_hist8 = ($this->k00_hist8 == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist8"]:$this->k00_hist8);
       $this->linhadigitavel = ($this->linhadigitavel == ""?@$GLOBALS["HTTP_POST_VARS"]["linhadigitavel"]:$this->linhadigitavel);
       $this->codigobarras = ($this->codigobarras == ""?@$GLOBALS["HTTP_POST_VARS"]["codigobarras"]:$this->codigobarras);
       $this->vlrtotal = ($this->vlrtotal == ""?@$GLOBALS["HTTP_POST_VARS"]["vlrtotal"]:$this->vlrtotal);
       if($this->vencimento == ""){
         $this->vencimento_dia = ($this->vencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["vencimento_dia"]:$this->vencimento_dia);
         $this->vencimento_mes = ($this->vencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["vencimento_mes"]:$this->vencimento_mes);
         $this->vencimento_ano = ($this->vencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["vencimento_ano"]:$this->vencimento_ano);
         if($this->vencimento_dia != ""){
            $this->vencimento = $this->vencimento_ano."-".$this->vencimento_mes."-".$this->vencimento_dia;
         }
       }
       $this->ip = ($this->ip == ""?@$GLOBALS["HTTP_POST_VARS"]["ip"]:$this->ip);
       $this->numpre = ($this->numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["numpre"]:$this->numpre);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->idcampos == null ){ 
       $this->erro_sql = " Campo Id Campos nao Informado.";
       $this->erro_campo = "idcampos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->codmodelo == null ){ 
       $this->erro_sql = " Campo Código Modelo nao Informado.";
       $this->erro_campo = "codmodelo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_nome == null ){ 
       $this->erro_sql = " Campo Nome/Razão Social nao Informado.";
       $this->erro_campo = "z01_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_ender == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "z01_ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_cep == null ){ 
       $this->erro_sql = " Campo CEP nao Informado.";
       $this->erro_campo = "z01_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_codbco == null ){ 
       $this->erro_sql = " Campo codigo do banco nao Informado.";
       $this->erro_campo = "k00_codbco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_codage == null ){ 
       $this->erro_sql = " Campo codigo da agencia nao Informado.";
       $this->erro_campo = "k00_codage";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_txban == null ){ 
       $this->k00_txban = "0";
     }
     if($this->k00_rectx == null ){ 
       $this->k00_rectx = "0";
     }
     if($this->j34_setor == null ){ 
       $this->erro_sql = " Campo Setor nao Informado.";
       $this->erro_campo = "j34_setor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j34_quadra == null ){ 
       $this->erro_sql = " Campo Quadra nao Informado.";
       $this->erro_campo = "j34_quadra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j34_lote == null ){ 
       $this->erro_sql = " Campo Lote nao Informado.";
       $this->erro_campo = "j34_lote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j39_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "j39_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j01_matric == null ){ 
       $this->erro_sql = " Campo Matrícula do Imóvel nao Informado.";
       $this->erro_campo = "j01_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q02_inscr == null ){ 
       $this->erro_sql = " Campo Inscrição Municipal nao Informado.";
       $this->erro_campo = "q02_inscr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_local == null ){ 
       $this->erro_sql = " Campo local nao Informado.";
       $this->erro_campo = "k15_local";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_aceite == null ){ 
       $this->erro_sql = " Campo aceite nao Informado.";
       $this->erro_campo = "k15_aceite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_carte == null ){ 
       $this->erro_sql = " Campo carteira nao Informado.";
       $this->erro_campo = "k15_carte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_espec == null ){ 
       $this->erro_sql = " Campo especie do documento nao Informado.";
       $this->erro_campo = "k15_espec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_gerent == null ){ 
       $this->erro_sql = " Campo gerente nao Informado.";
       $this->erro_campo = "k15_gerent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k15_ageced == null ){ 
       $this->erro_sql = " Campo agencia do cedente nao Informado.";
       $this->erro_campo = "k15_ageced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vlrtotal == null ){ 
       $this->vlrtotal = "0";
     }
     if($this->ip == null ){ 
       $this->erro_sql = " Campo IP nao Informado.";
       $this->erro_campo = "ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->numpre == null ){ 
       $this->erro_sql = " Campo Nº arrecadação nao Informado.";
       $this->erro_campo = "numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_carnesdados(
                                       idcampos 
                                      ,codmodelo 
                                      ,z01_nome 
                                      ,z01_ender 
                                      ,z01_munic 
                                      ,z01_cep 
                                      ,z01_uf 
                                      ,z01_bairro 
                                      ,k00_codbco 
                                      ,k00_codage 
                                      ,k00_txban 
                                      ,k00_rectx 
                                      ,j34_setor 
                                      ,j34_quadra 
                                      ,j34_lote 
                                      ,j39_numero 
                                      ,j39_compl 
                                      ,j01_matric 
                                      ,q02_inscr 
                                      ,k15_local 
                                      ,k15_aceite 
                                      ,k15_carte 
                                      ,k15_espec 
                                      ,k15_gerent 
                                      ,k15_ageced 
                                      ,k00_hist1 
                                      ,k00_hist2 
                                      ,k00_hist3 
                                      ,k00_hist4 
                                      ,k00_hist5 
                                      ,k00_hist6 
                                      ,k00_hist7 
                                      ,k00_hist8 
                                      ,linhadigitavel 
                                      ,codigobarras 
                                      ,vlrtotal 
                                      ,vencimento 
                                      ,ip 
                                      ,numpre 
                       )
                values (
                                $this->idcampos 
                               ,$this->codmodelo 
                               ,'$this->z01_nome' 
                               ,'$this->z01_ender' 
                               ,'$this->z01_munic' 
                               ,'$this->z01_cep' 
                               ,'$this->z01_uf' 
                               ,'$this->z01_bairro' 
                               ,$this->k00_codbco 
                               ,'$this->k00_codage' 
                               ,$this->k00_txban 
                               ,$this->k00_rectx 
                               ,'$this->j34_setor' 
                               ,'$this->j34_quadra' 
                               ,'$this->j34_lote' 
                               ,$this->j39_numero 
                               ,'$this->j39_compl' 
                               ,$this->j01_matric 
                               ,$this->q02_inscr 
                               ,'$this->k15_local' 
                               ,'$this->k15_aceite' 
                               ,'$this->k15_carte' 
                               ,'$this->k15_espec' 
                               ,'$this->k15_gerent' 
                               ,'$this->k15_ageced' 
                               ,'$this->k00_hist1' 
                               ,'$this->k00_hist2' 
                               ,'$this->k00_hist3' 
                               ,'$this->k00_hist4' 
                               ,'$this->k00_hist5' 
                               ,'$this->k00_hist6' 
                               ,'$this->k00_hist7' 
                               ,'$this->k00_hist8' 
                               ,'$this->linhadigitavel' 
                               ,'$this->codigobarras' 
                               ,$this->vlrtotal 
                               ,".($this->vencimento == "null" || $this->vencimento == ""?"null":"'".$this->vencimento."'")." 
                               ,'$this->ip' 
                               ,'$this->numpre' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados a Imprimir () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados a Imprimir já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados a Imprimir () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update db_carnesdados set ";
     $virgula = "";
     if(trim($this->idcampos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["idcampos"])){ 
       $sql  .= $virgula." idcampos = $this->idcampos ";
       $virgula = ",";
       if(trim($this->idcampos) == null ){ 
         $this->erro_sql = " Campo Id Campos nao Informado.";
         $this->erro_campo = "idcampos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codmodelo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codmodelo"])){ 
       $sql  .= $virgula." codmodelo = $this->codmodelo ";
       $virgula = ",";
       if(trim($this->codmodelo) == null ){ 
         $this->erro_sql = " Campo Código Modelo nao Informado.";
         $this->erro_campo = "codmodelo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_nome"])){ 
       $sql  .= $virgula." z01_nome = '$this->z01_nome' ";
       $virgula = ",";
       if(trim($this->z01_nome) == null ){ 
         $this->erro_sql = " Campo Nome/Razão Social nao Informado.";
         $this->erro_campo = "z01_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_ender"])){ 
       $sql  .= $virgula." z01_ender = '$this->z01_ender' ";
       $virgula = ",";
       if(trim($this->z01_ender) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "z01_ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_munic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_munic"])){ 
       $sql  .= $virgula." z01_munic = '$this->z01_munic' ";
       $virgula = ",";
     }
     if(trim($this->z01_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_cep"])){ 
       $sql  .= $virgula." z01_cep = '$this->z01_cep' ";
       $virgula = ",";
       if(trim($this->z01_cep) == null ){ 
         $this->erro_sql = " Campo CEP nao Informado.";
         $this->erro_campo = "z01_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_uf"])){ 
       $sql  .= $virgula." z01_uf = '$this->z01_uf' ";
       $virgula = ",";
     }
     if(trim($this->z01_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_bairro"])){ 
       $sql  .= $virgula." z01_bairro = '$this->z01_bairro' ";
       $virgula = ",";
     }
     if(trim($this->k00_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_codbco"])){ 
       $sql  .= $virgula." k00_codbco = $this->k00_codbco ";
       $virgula = ",";
       if(trim($this->k00_codbco) == null ){ 
         $this->erro_sql = " Campo codigo do banco nao Informado.";
         $this->erro_campo = "k00_codbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_codage"])){ 
       $sql  .= $virgula." k00_codage = '$this->k00_codage' ";
       $virgula = ",";
       if(trim($this->k00_codage) == null ){ 
         $this->erro_sql = " Campo codigo da agencia nao Informado.";
         $this->erro_campo = "k00_codage";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_txban)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_txban"])){ 
        if(trim($this->k00_txban)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k00_txban"])){ 
           $this->k00_txban = "0" ; 
        } 
       $sql  .= $virgula." k00_txban = $this->k00_txban ";
       $virgula = ",";
     }
     if(trim($this->k00_rectx)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_rectx"])){ 
        if(trim($this->k00_rectx)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k00_rectx"])){ 
           $this->k00_rectx = "0" ; 
        } 
       $sql  .= $virgula." k00_rectx = $this->k00_rectx ";
       $virgula = ",";
     }
     if(trim($this->j34_setor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j34_setor"])){ 
       $sql  .= $virgula." j34_setor = '$this->j34_setor' ";
       $virgula = ",";
       if(trim($this->j34_setor) == null ){ 
         $this->erro_sql = " Campo Setor nao Informado.";
         $this->erro_campo = "j34_setor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j34_quadra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j34_quadra"])){ 
       $sql  .= $virgula." j34_quadra = '$this->j34_quadra' ";
       $virgula = ",";
       if(trim($this->j34_quadra) == null ){ 
         $this->erro_sql = " Campo Quadra nao Informado.";
         $this->erro_campo = "j34_quadra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j34_lote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j34_lote"])){ 
       $sql  .= $virgula." j34_lote = '$this->j34_lote' ";
       $virgula = ",";
       if(trim($this->j34_lote) == null ){ 
         $this->erro_sql = " Campo Lote nao Informado.";
         $this->erro_campo = "j34_lote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j39_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_numero"])){ 
       $sql  .= $virgula." j39_numero = $this->j39_numero ";
       $virgula = ",";
       if(trim($this->j39_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "j39_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j39_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j39_compl"])){ 
       $sql  .= $virgula." j39_compl = '$this->j39_compl' ";
       $virgula = ",";
     }
     if(trim($this->j01_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j01_matric"])){ 
       $sql  .= $virgula." j01_matric = $this->j01_matric ";
       $virgula = ",";
       if(trim($this->j01_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula do Imóvel nao Informado.";
         $this->erro_campo = "j01_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q02_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q02_inscr"])){ 
       $sql  .= $virgula." q02_inscr = $this->q02_inscr ";
       $virgula = ",";
       if(trim($this->q02_inscr) == null ){ 
         $this->erro_sql = " Campo Inscrição Municipal nao Informado.";
         $this->erro_campo = "q02_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_local)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_local"])){ 
       $sql  .= $virgula." k15_local = '$this->k15_local' ";
       $virgula = ",";
       if(trim($this->k15_local) == null ){ 
         $this->erro_sql = " Campo local nao Informado.";
         $this->erro_campo = "k15_local";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_aceite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_aceite"])){ 
       $sql  .= $virgula." k15_aceite = '$this->k15_aceite' ";
       $virgula = ",";
       if(trim($this->k15_aceite) == null ){ 
         $this->erro_sql = " Campo aceite nao Informado.";
         $this->erro_campo = "k15_aceite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_carte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_carte"])){ 
       $sql  .= $virgula." k15_carte = '$this->k15_carte' ";
       $virgula = ",";
       if(trim($this->k15_carte) == null ){ 
         $this->erro_sql = " Campo carteira nao Informado.";
         $this->erro_campo = "k15_carte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_espec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_espec"])){ 
       $sql  .= $virgula." k15_espec = '$this->k15_espec' ";
       $virgula = ",";
       if(trim($this->k15_espec) == null ){ 
         $this->erro_sql = " Campo especie do documento nao Informado.";
         $this->erro_campo = "k15_espec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_gerent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_gerent"])){ 
       $sql  .= $virgula." k15_gerent = '$this->k15_gerent' ";
       $virgula = ",";
       if(trim($this->k15_gerent) == null ){ 
         $this->erro_sql = " Campo gerente nao Informado.";
         $this->erro_campo = "k15_gerent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k15_ageced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k15_ageced"])){ 
       $sql  .= $virgula." k15_ageced = '$this->k15_ageced' ";
       $virgula = ",";
       if(trim($this->k15_ageced) == null ){ 
         $this->erro_sql = " Campo agencia do cedente nao Informado.";
         $this->erro_campo = "k15_ageced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_hist1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist1"])){ 
       $sql  .= $virgula." k00_hist1 = '$this->k00_hist1' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist2"])){ 
       $sql  .= $virgula." k00_hist2 = '$this->k00_hist2' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist3"])){ 
       $sql  .= $virgula." k00_hist3 = '$this->k00_hist3' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist4"])){ 
       $sql  .= $virgula." k00_hist4 = '$this->k00_hist4' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist5"])){ 
       $sql  .= $virgula." k00_hist5 = '$this->k00_hist5' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist6)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist6"])){ 
       $sql  .= $virgula." k00_hist6 = '$this->k00_hist6' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist7)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist7"])){ 
       $sql  .= $virgula." k00_hist7 = '$this->k00_hist7' ";
       $virgula = ",";
     }
     if(trim($this->k00_hist8)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist8"])){ 
       $sql  .= $virgula." k00_hist8 = '$this->k00_hist8' ";
       $virgula = ",";
     }
     if(trim($this->linhadigitavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["linhadigitavel"])){ 
       $sql  .= $virgula." linhadigitavel = '$this->linhadigitavel' ";
       $virgula = ",";
     }
     if(trim($this->codigobarras)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codigobarras"])){ 
       $sql  .= $virgula." codigobarras = '$this->codigobarras' ";
       $virgula = ",";
     }
     if(trim($this->vlrtotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vlrtotal"])){ 
        if(trim($this->vlrtotal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vlrtotal"])){ 
           $this->vlrtotal = "0" ; 
        } 
       $sql  .= $virgula." vlrtotal = $this->vlrtotal ";
       $virgula = ",";
     }
     if(trim($this->vencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["vencimento_dia"] !="") ){ 
       $sql  .= $virgula." vencimento = '$this->vencimento' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["vencimento_dia"])){ 
         $sql  .= $virgula." vencimento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ip"])){ 
       $sql  .= $virgula." ip = '$this->ip' ";
       $virgula = ",";
       if(trim($this->ip) == null ){ 
         $this->erro_sql = " Campo IP nao Informado.";
         $this->erro_campo = "ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["numpre"])){ 
       $sql  .= $virgula." numpre = '$this->numpre' ";
       $virgula = ",";
       if(trim($this->numpre) == null ){ 
         $this->erro_sql = " Campo Nº arrecadação nao Informado.";
         $this->erro_campo = "numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados a Imprimir nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados a Imprimir nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from db_carnesdados
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados a Imprimir nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados a Imprimir nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:db_carnesdados";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>