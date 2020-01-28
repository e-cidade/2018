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

//MODULO: protocolo
//CLASSE DA ENTIDADE ceplogradouros
class cl_ceplogradouros { 
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
   var $cp06_sequencial = 0; 
   var $cp06_codlogradouro = 0; 
   var $cp06_codbairroinicial = 0; 
   var $cp06_codbairrofinal = 0; 
   var $cp06_logradouro = null; 
   var $cp06_adicional = null; 
   var $cp06_cep = null; 
   var $cp06_grandeusuario = null; 
   var $cp06_numinicial = 0; 
   var $cp06_numfinal = 0; 
   var $cp06_lado = null; 
   var $cp06_codseccao = 0; 
   var $cp06_sigla = null; 
   var $cp06_codlocalidade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cp06_sequencial = int4 = Sequencial 
                 cp06_codlogradouro = int8 = Código do Logradouro 
                 cp06_codbairroinicial = int8 = Bairro Inicial 
                 cp06_codbairrofinal = int8 = Bairro Final 
                 cp06_logradouro = varchar(72) = Logradouro 
                 cp06_adicional = varchar(72) = Adicional 
                 cp06_cep = varchar(8) = Cep 
                 cp06_grandeusuario = varchar(1) = Grande Usuario 
                 cp06_numinicial = int8 = Número Inicial 
                 cp06_numfinal = int8 = Número Final 
                 cp06_lado = varchar(1) = Lado 
                 cp06_codseccao = int8 = Codigo Secção 
                 cp06_sigla = varchar(2) = Sigla Estado 
                 cp06_codlocalidade = int8 = Codigo da Localidade 
                 ";
   //funcao construtor da classe 
   function cl_ceplogradouros() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ceplogradouros"); 
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
       $this->cp06_sequencial = ($this->cp06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_sequencial"]:$this->cp06_sequencial);
       $this->cp06_codlogradouro = ($this->cp06_codlogradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_codlogradouro"]:$this->cp06_codlogradouro);
       $this->cp06_codbairroinicial = ($this->cp06_codbairroinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_codbairroinicial"]:$this->cp06_codbairroinicial);
       $this->cp06_codbairrofinal = ($this->cp06_codbairrofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_codbairrofinal"]:$this->cp06_codbairrofinal);
       $this->cp06_logradouro = ($this->cp06_logradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_logradouro"]:$this->cp06_logradouro);
       $this->cp06_adicional = ($this->cp06_adicional == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_adicional"]:$this->cp06_adicional);
       $this->cp06_cep = ($this->cp06_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_cep"]:$this->cp06_cep);
       $this->cp06_grandeusuario = ($this->cp06_grandeusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_grandeusuario"]:$this->cp06_grandeusuario);
       $this->cp06_numinicial = ($this->cp06_numinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_numinicial"]:$this->cp06_numinicial);
       $this->cp06_numfinal = ($this->cp06_numfinal == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_numfinal"]:$this->cp06_numfinal);
       $this->cp06_lado = ($this->cp06_lado == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_lado"]:$this->cp06_lado);
       $this->cp06_codseccao = ($this->cp06_codseccao == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_codseccao"]:$this->cp06_codseccao);
       $this->cp06_sigla = ($this->cp06_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_sigla"]:$this->cp06_sigla);
       $this->cp06_codlocalidade = ($this->cp06_codlocalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_codlocalidade"]:$this->cp06_codlocalidade);
     }else{
       $this->cp06_sequencial = ($this->cp06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cp06_sequencial"]:$this->cp06_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cp06_sequencial){ 
      $this->atualizacampos();
     if($this->cp06_codlogradouro == null ){ 
       $this->erro_sql = " Campo Código do Logradouro nao Informado.";
       $this->erro_campo = "cp06_codlogradouro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp06_codbairroinicial == null ){ 
       $this->erro_sql = " Campo Bairro Inicial nao Informado.";
       $this->erro_campo = "cp06_codbairroinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp06_codbairrofinal == null ){ 
       $this->erro_sql = " Campo Bairro Final nao Informado.";
       $this->erro_campo = "cp06_codbairrofinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp06_logradouro == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "cp06_logradouro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp06_adicional == null ){ 
       $this->erro_sql = " Campo Adicional nao Informado.";
       $this->erro_campo = "cp06_adicional";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp06_cep == null ){ 
       $this->erro_sql = " Campo Cep nao Informado.";
       $this->erro_campo = "cp06_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp06_grandeusuario == null ){ 
       $this->erro_sql = " Campo Grande Usuario nao Informado.";
       $this->erro_campo = "cp06_grandeusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp06_numinicial == null ){ 
       $this->erro_sql = " Campo Número Inicial nao Informado.";
       $this->erro_campo = "cp06_numinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp06_numfinal == null ){ 
       $this->erro_sql = " Campo Número Final nao Informado.";
       $this->erro_campo = "cp06_numfinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp06_lado == null ){ 
       $this->erro_sql = " Campo Lado nao Informado.";
       $this->erro_campo = "cp06_lado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp06_codseccao == null ){ 
       $this->erro_sql = " Campo Codigo Secção nao Informado.";
       $this->erro_campo = "cp06_codseccao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp06_sigla == null ){ 
       $this->erro_sql = " Campo Sigla Estado nao Informado.";
       $this->erro_campo = "cp06_sigla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp06_codlocalidade == null ){ 
       $this->erro_sql = " Campo Codigo da Localidade nao Informado.";
       $this->erro_campo = "cp06_codlocalidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cp06_sequencial == "" || $cp06_sequencial == null ){
       $result = db_query("select nextval('ceplogradouros_cp06_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ceplogradouros_cp06_sequencial_seq do campo: cp06_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cp06_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ceplogradouros_cp06_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cp06_sequencial)){
         $this->erro_sql = " Campo cp06_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cp06_sequencial = $cp06_sequencial; 
       }
     }
     if(($this->cp06_sequencial == null) || ($this->cp06_sequencial == "") ){ 
       $this->erro_sql = " Campo cp06_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ceplogradouros(
                                       cp06_sequencial 
                                      ,cp06_codlogradouro 
                                      ,cp06_codbairroinicial 
                                      ,cp06_codbairrofinal 
                                      ,cp06_logradouro 
                                      ,cp06_adicional 
                                      ,cp06_cep 
                                      ,cp06_grandeusuario 
                                      ,cp06_numinicial 
                                      ,cp06_numfinal 
                                      ,cp06_lado 
                                      ,cp06_codseccao 
                                      ,cp06_sigla 
                                      ,cp06_codlocalidade 
                       )
                values (
                                $this->cp06_sequencial 
                               ,$this->cp06_codlogradouro 
                               ,$this->cp06_codbairroinicial 
                               ,$this->cp06_codbairrofinal 
                               ,'$this->cp06_logradouro' 
                               ,'$this->cp06_adicional' 
                               ,'$this->cp06_cep' 
                               ,'$this->cp06_grandeusuario' 
                               ,$this->cp06_numinicial 
                               ,$this->cp06_numfinal 
                               ,'$this->cp06_lado' 
                               ,$this->cp06_codseccao 
                               ,'$this->cp06_sigla' 
                               ,$this->cp06_codlocalidade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Logradouros ($this->cp06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Logradouros já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Logradouros ($this->cp06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cp06_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cp06_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9850,'$this->cp06_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1197,9850,'','".AddSlashes(pg_result($resaco,0,'cp06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7197,'','".AddSlashes(pg_result($resaco,0,'cp06_codlogradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7198,'','".AddSlashes(pg_result($resaco,0,'cp06_codbairroinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7199,'','".AddSlashes(pg_result($resaco,0,'cp06_codbairrofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7200,'','".AddSlashes(pg_result($resaco,0,'cp06_logradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7201,'','".AddSlashes(pg_result($resaco,0,'cp06_adicional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7202,'','".AddSlashes(pg_result($resaco,0,'cp06_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7217,'','".AddSlashes(pg_result($resaco,0,'cp06_grandeusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7203,'','".AddSlashes(pg_result($resaco,0,'cp06_numinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7204,'','".AddSlashes(pg_result($resaco,0,'cp06_numfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7205,'','".AddSlashes(pg_result($resaco,0,'cp06_lado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7206,'','".AddSlashes(pg_result($resaco,0,'cp06_codseccao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7207,'','".AddSlashes(pg_result($resaco,0,'cp06_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1197,7208,'','".AddSlashes(pg_result($resaco,0,'cp06_codlocalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cp06_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ceplogradouros set ";
     $virgula = "";
     if(trim($this->cp06_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_sequencial"])){ 
       $sql  .= $virgula." cp06_sequencial = $this->cp06_sequencial ";
       $virgula = ",";
       if(trim($this->cp06_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "cp06_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_codlogradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_codlogradouro"])){ 
       $sql  .= $virgula." cp06_codlogradouro = $this->cp06_codlogradouro ";
       $virgula = ",";
       if(trim($this->cp06_codlogradouro) == null ){ 
         $this->erro_sql = " Campo Código do Logradouro nao Informado.";
         $this->erro_campo = "cp06_codlogradouro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_codbairroinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_codbairroinicial"])){ 
       $sql  .= $virgula." cp06_codbairroinicial = $this->cp06_codbairroinicial ";
       $virgula = ",";
       if(trim($this->cp06_codbairroinicial) == null ){ 
         $this->erro_sql = " Campo Bairro Inicial nao Informado.";
         $this->erro_campo = "cp06_codbairroinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_codbairrofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_codbairrofinal"])){ 
       $sql  .= $virgula." cp06_codbairrofinal = $this->cp06_codbairrofinal ";
       $virgula = ",";
       if(trim($this->cp06_codbairrofinal) == null ){ 
         $this->erro_sql = " Campo Bairro Final nao Informado.";
         $this->erro_campo = "cp06_codbairrofinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_logradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_logradouro"])){ 
       $sql  .= $virgula." cp06_logradouro = '$this->cp06_logradouro' ";
       $virgula = ",";
       if(trim($this->cp06_logradouro) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "cp06_logradouro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_adicional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_adicional"])){ 
       $sql  .= $virgula." cp06_adicional = '$this->cp06_adicional' ";
       $virgula = ",";
       if(trim($this->cp06_adicional) == null ){ 
         $this->erro_sql = " Campo Adicional nao Informado.";
         $this->erro_campo = "cp06_adicional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_cep"])){ 
       $sql  .= $virgula." cp06_cep = '$this->cp06_cep' ";
       $virgula = ",";
       if(trim($this->cp06_cep) == null ){ 
         $this->erro_sql = " Campo Cep nao Informado.";
         $this->erro_campo = "cp06_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_grandeusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_grandeusuario"])){ 
       $sql  .= $virgula." cp06_grandeusuario = '$this->cp06_grandeusuario' ";
       $virgula = ",";
       if(trim($this->cp06_grandeusuario) == null ){ 
         $this->erro_sql = " Campo Grande Usuario nao Informado.";
         $this->erro_campo = "cp06_grandeusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_numinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_numinicial"])){ 
       $sql  .= $virgula." cp06_numinicial = $this->cp06_numinicial ";
       $virgula = ",";
       if(trim($this->cp06_numinicial) == null ){ 
         $this->erro_sql = " Campo Número Inicial nao Informado.";
         $this->erro_campo = "cp06_numinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_numfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_numfinal"])){ 
       $sql  .= $virgula." cp06_numfinal = $this->cp06_numfinal ";
       $virgula = ",";
       if(trim($this->cp06_numfinal) == null ){ 
         $this->erro_sql = " Campo Número Final nao Informado.";
         $this->erro_campo = "cp06_numfinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_lado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_lado"])){ 
       $sql  .= $virgula." cp06_lado = '$this->cp06_lado' ";
       $virgula = ",";
       if(trim($this->cp06_lado) == null ){ 
         $this->erro_sql = " Campo Lado nao Informado.";
         $this->erro_campo = "cp06_lado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_codseccao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_codseccao"])){ 
       $sql  .= $virgula." cp06_codseccao = $this->cp06_codseccao ";
       $virgula = ",";
       if(trim($this->cp06_codseccao) == null ){ 
         $this->erro_sql = " Campo Codigo Secção nao Informado.";
         $this->erro_campo = "cp06_codseccao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_sigla"])){ 
       $sql  .= $virgula." cp06_sigla = '$this->cp06_sigla' ";
       $virgula = ",";
       if(trim($this->cp06_sigla) == null ){ 
         $this->erro_sql = " Campo Sigla Estado nao Informado.";
         $this->erro_campo = "cp06_sigla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp06_codlocalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp06_codlocalidade"])){ 
       $sql  .= $virgula." cp06_codlocalidade = $this->cp06_codlocalidade ";
       $virgula = ",";
       if(trim($this->cp06_codlocalidade) == null ){ 
         $this->erro_sql = " Campo Codigo da Localidade nao Informado.";
         $this->erro_campo = "cp06_codlocalidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cp06_sequencial!=null){
       $sql .= " cp06_sequencial = $this->cp06_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cp06_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9850,'$this->cp06_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1197,9850,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_sequencial'))."','$this->cp06_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_codlogradouro"]))
           $resac = db_query("insert into db_acount values($acount,1197,7197,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_codlogradouro'))."','$this->cp06_codlogradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_codbairroinicial"]))
           $resac = db_query("insert into db_acount values($acount,1197,7198,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_codbairroinicial'))."','$this->cp06_codbairroinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_codbairrofinal"]))
           $resac = db_query("insert into db_acount values($acount,1197,7199,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_codbairrofinal'))."','$this->cp06_codbairrofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_logradouro"]))
           $resac = db_query("insert into db_acount values($acount,1197,7200,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_logradouro'))."','$this->cp06_logradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_adicional"]))
           $resac = db_query("insert into db_acount values($acount,1197,7201,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_adicional'))."','$this->cp06_adicional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_cep"]))
           $resac = db_query("insert into db_acount values($acount,1197,7202,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_cep'))."','$this->cp06_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_grandeusuario"]))
           $resac = db_query("insert into db_acount values($acount,1197,7217,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_grandeusuario'))."','$this->cp06_grandeusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_numinicial"]))
           $resac = db_query("insert into db_acount values($acount,1197,7203,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_numinicial'))."','$this->cp06_numinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_numfinal"]))
           $resac = db_query("insert into db_acount values($acount,1197,7204,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_numfinal'))."','$this->cp06_numfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_lado"]))
           $resac = db_query("insert into db_acount values($acount,1197,7205,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_lado'))."','$this->cp06_lado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_codseccao"]))
           $resac = db_query("insert into db_acount values($acount,1197,7206,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_codseccao'))."','$this->cp06_codseccao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_sigla"]))
           $resac = db_query("insert into db_acount values($acount,1197,7207,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_sigla'))."','$this->cp06_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp06_codlocalidade"]))
           $resac = db_query("insert into db_acount values($acount,1197,7208,'".AddSlashes(pg_result($resaco,$conresaco,'cp06_codlocalidade'))."','$this->cp06_codlocalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Logradouros nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cp06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Logradouros nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cp06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cp06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cp06_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cp06_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9850,'$cp06_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1197,9850,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7197,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_codlogradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7198,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_codbairroinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7199,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_codbairrofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7200,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_logradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7201,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_adicional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7202,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7217,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_grandeusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7203,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_numinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7204,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_numfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7205,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_lado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7206,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_codseccao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7207,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1197,7208,'','".AddSlashes(pg_result($resaco,$iresaco,'cp06_codlocalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ceplogradouros
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cp06_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cp06_sequencial = $cp06_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Logradouros nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cp06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Logradouros nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cp06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cp06_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ceplogradouros";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cp06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ceplogradouros ";
     $sql .= "      inner join ceplocalidades  on  ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade";
     $sql .= "      inner join cepestados  on  cepestados.cp03_sigla = ceplocalidades.cp05_sigla";
     $sql2 = "";
     if($dbwhere==""){
       if($cp06_sequencial!=null ){
         $sql2 .= " where ceplogradouros.cp06_sequencial = $cp06_sequencial "; 
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
   function sql_query_file ( $cp06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ceplogradouros ";
     $sql2 = "";
     if($dbwhere==""){
       if($cp06_sequencial!=null ){
         $sql2 .= " where ceplogradouros.cp06_sequencial = $cp06_sequencial "; 
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
}
?>