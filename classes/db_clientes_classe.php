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

//MODULO: Atendimento
//CLASSE DA ENTIDADE clientes
class cl_clientes { 
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
   var $at01_codcli = 0; 
   var $at01_nomecli = null; 
   var $at01_site = null; 
   var $at01_status = 'f'; 
   var $at01_cidade = null; 
   var $at01_ender = null; 
   var $at01_cep = null; 
   var $at01_codver = 0; 
   var $at01_sigla = null; 
   var $at01_ativo = 'f'; 
   var $at01_base = 'f'; 
   var $at01_obs = null; 
   var $at01_cnpj = null; 
   var $at01_uf = 0; 
   var $at01_telefone = null; 
   var $at01_tipocliente = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at01_codcli = int4 = Código do cliente 
                 at01_nomecli = varchar(40) = Nome do cliente 
                 at01_site = varchar(40) = Site 
                 at01_status = bool = Ativo 
                 at01_cidade = varchar(40) = Cidade 
                 at01_ender = varchar(40) = Endereço 
                 at01_cep = varchar(10) = Cep 
                 at01_codver = int4 = Código da Versão 
                 at01_sigla = varchar(10) = Sigla 
                 at01_ativo = bool = Atualiza Versão 
                 at01_base = bool = Base 
                 at01_obs = text = Observação 
                 at01_cnpj = varchar(14) = CNPJ 
                 at01_uf = int4 = UF 
                 at01_telefone = varchar(14) = Telefone 
                 at01_tipocliente = int4 = Tipo de Cliente 
                 ";
   //funcao construtor da classe 
   function cl_clientes() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("clientes"); 
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
       $this->at01_codcli = ($this->at01_codcli == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_codcli"]:$this->at01_codcli);
       $this->at01_nomecli = ($this->at01_nomecli == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_nomecli"]:$this->at01_nomecli);
       $this->at01_site = ($this->at01_site == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_site"]:$this->at01_site);
       $this->at01_status = ($this->at01_status == "f"?@$GLOBALS["HTTP_POST_VARS"]["at01_status"]:$this->at01_status);
       $this->at01_cidade = ($this->at01_cidade == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_cidade"]:$this->at01_cidade);
       $this->at01_ender = ($this->at01_ender == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_ender"]:$this->at01_ender);
       $this->at01_cep = ($this->at01_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_cep"]:$this->at01_cep);
       $this->at01_codver = ($this->at01_codver == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_codver"]:$this->at01_codver);
       $this->at01_sigla = ($this->at01_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_sigla"]:$this->at01_sigla);
       $this->at01_ativo = ($this->at01_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["at01_ativo"]:$this->at01_ativo);
       $this->at01_base = ($this->at01_base == "f"?@$GLOBALS["HTTP_POST_VARS"]["at01_base"]:$this->at01_base);
       $this->at01_obs = ($this->at01_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_obs"]:$this->at01_obs);
       $this->at01_cnpj = ($this->at01_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_cnpj"]:$this->at01_cnpj);
       $this->at01_uf = ($this->at01_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_uf"]:$this->at01_uf);
       $this->at01_telefone = ($this->at01_telefone == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_telefone"]:$this->at01_telefone);
       $this->at01_tipocliente = ($this->at01_tipocliente == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_tipocliente"]:$this->at01_tipocliente);
     }else{
       $this->at01_codcli = ($this->at01_codcli == ""?@$GLOBALS["HTTP_POST_VARS"]["at01_codcli"]:$this->at01_codcli);
     }
   }
   // funcao para inclusao
   function incluir ($at01_codcli){ 
      $this->atualizacampos();
     if($this->at01_nomecli == null ){ 
       $this->erro_sql = " Campo Nome do cliente nao Informado.";
       $this->erro_campo = "at01_nomecli";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at01_status == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "at01_status";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at01_cidade == null ){ 
       $this->erro_sql = " Campo Cidade nao Informado.";
       $this->erro_campo = "at01_cidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at01_ender == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "at01_ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at01_cep == null ){ 
       $this->erro_sql = " Campo Cep nao Informado.";
       $this->erro_campo = "at01_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at01_codver == null ){ 
       $this->at01_codver = "0";
     }
     if($this->at01_sigla == null ){ 
       $this->erro_sql = " Campo Sigla nao Informado.";
       $this->erro_campo = "at01_sigla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at01_ativo == null ){ 
       $this->erro_sql = " Campo Atualiza Versão nao Informado.";
       $this->erro_campo = "at01_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at01_base == null ){ 
       $this->erro_sql = " Campo Base nao Informado.";
       $this->erro_campo = "at01_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at01_cnpj == null ){ 
       $this->erro_sql = " Campo CNPJ nao Informado.";
       $this->erro_campo = "at01_cnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at01_uf == null ){ 
       $this->erro_sql = " Campo UF nao Informado.";
       $this->erro_campo = "at01_uf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at01_tipocliente == null ){ 
       $this->erro_sql = " Campo Tipo de Cliente nao Informado.";
       $this->erro_campo = "at01_tipocliente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at01_codcli == "" || $at01_codcli == null ){
       $result = db_query("select nextval('clientes_at01_codcli_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: clientes_at01_codcli_seq do campo: at01_codcli"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at01_codcli = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from clientes_at01_codcli_seq");
       if(($result != false) && (pg_result($result,0,0) < $at01_codcli)){
         $this->erro_sql = " Campo at01_codcli maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at01_codcli = $at01_codcli; 
       }
     }
     if(($this->at01_codcli == null) || ($this->at01_codcli == "") ){ 
       $this->erro_sql = " Campo at01_codcli nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into clientes(
                                       at01_codcli 
                                      ,at01_nomecli 
                                      ,at01_site 
                                      ,at01_status 
                                      ,at01_cidade 
                                      ,at01_ender 
                                      ,at01_cep 
                                      ,at01_codver 
                                      ,at01_sigla 
                                      ,at01_ativo 
                                      ,at01_base 
                                      ,at01_obs 
                                      ,at01_cnpj 
                                      ,at01_uf 
                                      ,at01_telefone 
                                      ,at01_tipocliente 
                       )
                values (
                                $this->at01_codcli 
                               ,'$this->at01_nomecli' 
                               ,'$this->at01_site' 
                               ,'$this->at01_status' 
                               ,'$this->at01_cidade' 
                               ,'$this->at01_ender' 
                               ,'$this->at01_cep' 
                               ,$this->at01_codver 
                               ,'$this->at01_sigla' 
                               ,'$this->at01_ativo' 
                               ,'$this->at01_base' 
                               ,'$this->at01_obs' 
                               ,'$this->at01_cnpj' 
                               ,$this->at01_uf 
                               ,'$this->at01_telefone' 
                               ,$this->at01_tipocliente 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Clientes ($this->at01_codcli) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Clientes já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Clientes ($this->at01_codcli) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at01_codcli;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at01_codcli));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2540,'$this->at01_codcli','I')");
       $resac = db_query("insert into db_acount values($acount,416,2540,'','".AddSlashes(pg_result($resaco,0,'at01_codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,2541,'','".AddSlashes(pg_result($resaco,0,'at01_nomecli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,2543,'','".AddSlashes(pg_result($resaco,0,'at01_site'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,2544,'','".AddSlashes(pg_result($resaco,0,'at01_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,2576,'','".AddSlashes(pg_result($resaco,0,'at01_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,2577,'','".AddSlashes(pg_result($resaco,0,'at01_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,2578,'','".AddSlashes(pg_result($resaco,0,'at01_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,7410,'','".AddSlashes(pg_result($resaco,0,'at01_codver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,8652,'','".AddSlashes(pg_result($resaco,0,'at01_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,8987,'','".AddSlashes(pg_result($resaco,0,'at01_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,10731,'','".AddSlashes(pg_result($resaco,0,'at01_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,12105,'','".AddSlashes(pg_result($resaco,0,'at01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,17067,'','".AddSlashes(pg_result($resaco,0,'at01_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,17068,'','".AddSlashes(pg_result($resaco,0,'at01_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,17069,'','".AddSlashes(pg_result($resaco,0,'at01_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,416,17070,'','".AddSlashes(pg_result($resaco,0,'at01_tipocliente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at01_codcli=null) { 
      $this->atualizacampos();
     $sql = " update clientes set ";
     $virgula = "";
     if(trim($this->at01_codcli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_codcli"])){ 
       $sql  .= $virgula." at01_codcli = $this->at01_codcli ";
       $virgula = ",";
       if(trim($this->at01_codcli) == null ){ 
         $this->erro_sql = " Campo Código do cliente nao Informado.";
         $this->erro_campo = "at01_codcli";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at01_nomecli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_nomecli"])){ 
       $sql  .= $virgula." at01_nomecli = '$this->at01_nomecli' ";
       $virgula = ",";
       if(trim($this->at01_nomecli) == null ){ 
         $this->erro_sql = " Campo Nome do cliente nao Informado.";
         $this->erro_campo = "at01_nomecli";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at01_site)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_site"])){ 
       $sql  .= $virgula." at01_site = '$this->at01_site' ";
       $virgula = ",";
     }
     if(trim($this->at01_status)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_status"])){ 
       $sql  .= $virgula." at01_status = '$this->at01_status' ";
       $virgula = ",";
       if(trim($this->at01_status) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "at01_status";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at01_cidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_cidade"])){ 
       $sql  .= $virgula." at01_cidade = '$this->at01_cidade' ";
       $virgula = ",";
       if(trim($this->at01_cidade) == null ){ 
         $this->erro_sql = " Campo Cidade nao Informado.";
         $this->erro_campo = "at01_cidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at01_ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_ender"])){ 
       $sql  .= $virgula." at01_ender = '$this->at01_ender' ";
       $virgula = ",";
       if(trim($this->at01_ender) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "at01_ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at01_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_cep"])){ 
       $sql  .= $virgula." at01_cep = '$this->at01_cep' ";
       $virgula = ",";
       if(trim($this->at01_cep) == null ){ 
         $this->erro_sql = " Campo Cep nao Informado.";
         $this->erro_campo = "at01_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at01_codver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_codver"])){ 
        if(trim($this->at01_codver)=="" && isset($GLOBALS["HTTP_POST_VARS"]["at01_codver"])){ 
           $this->at01_codver = "0" ; 
        } 
       $sql  .= $virgula." at01_codver = $this->at01_codver ";
       $virgula = ",";
     }
     if(trim($this->at01_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_sigla"])){ 
       $sql  .= $virgula." at01_sigla = '$this->at01_sigla' ";
       $virgula = ",";
       if(trim($this->at01_sigla) == null ){ 
         $this->erro_sql = " Campo Sigla nao Informado.";
         $this->erro_campo = "at01_sigla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at01_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_ativo"])){ 
       $sql  .= $virgula." at01_ativo = '$this->at01_ativo' ";
       $virgula = ",";
       if(trim($this->at01_ativo) == null ){ 
         $this->erro_sql = " Campo Atualiza Versão nao Informado.";
         $this->erro_campo = "at01_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at01_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_base"])){ 
       $sql  .= $virgula." at01_base = '$this->at01_base' ";
       $virgula = ",";
       if(trim($this->at01_base) == null ){ 
         $this->erro_sql = " Campo Base nao Informado.";
         $this->erro_campo = "at01_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at01_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_obs"])){ 
       $sql  .= $virgula." at01_obs = '$this->at01_obs' ";
       $virgula = ",";
     }
     if(trim($this->at01_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_cnpj"])){ 
       $sql  .= $virgula." at01_cnpj = '$this->at01_cnpj' ";
       $virgula = ",";
       if(trim($this->at01_cnpj) == null ){ 
         $this->erro_sql = " Campo CNPJ nao Informado.";
         $this->erro_campo = "at01_cnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at01_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_uf"])){ 
       $sql  .= $virgula." at01_uf = $this->at01_uf ";
       $virgula = ",";
       if(trim($this->at01_uf) == null ){ 
         $this->erro_sql = " Campo UF nao Informado.";
         $this->erro_campo = "at01_uf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at01_telefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_telefone"])){ 
       $sql  .= $virgula." at01_telefone = '$this->at01_telefone' ";
       $virgula = ",";
     }
     if(trim($this->at01_tipocliente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at01_tipocliente"])){ 
       $sql  .= $virgula." at01_tipocliente = $this->at01_tipocliente ";
       $virgula = ",";
       if(trim($this->at01_tipocliente) == null ){ 
         $this->erro_sql = " Campo Tipo de Cliente nao Informado.";
         $this->erro_campo = "at01_tipocliente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at01_codcli!=null){
       $sql .= " at01_codcli = $this->at01_codcli";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at01_codcli));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2540,'$this->at01_codcli','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_codcli"]) || $this->at01_codcli != "")
           $resac = db_query("insert into db_acount values($acount,416,2540,'".AddSlashes(pg_result($resaco,$conresaco,'at01_codcli'))."','$this->at01_codcli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_nomecli"]) || $this->at01_nomecli != "")
           $resac = db_query("insert into db_acount values($acount,416,2541,'".AddSlashes(pg_result($resaco,$conresaco,'at01_nomecli'))."','$this->at01_nomecli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_site"]) || $this->at01_site != "")
           $resac = db_query("insert into db_acount values($acount,416,2543,'".AddSlashes(pg_result($resaco,$conresaco,'at01_site'))."','$this->at01_site',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_status"]) || $this->at01_status != "")
           $resac = db_query("insert into db_acount values($acount,416,2544,'".AddSlashes(pg_result($resaco,$conresaco,'at01_status'))."','$this->at01_status',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_cidade"]) || $this->at01_cidade != "")
           $resac = db_query("insert into db_acount values($acount,416,2576,'".AddSlashes(pg_result($resaco,$conresaco,'at01_cidade'))."','$this->at01_cidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_ender"]) || $this->at01_ender != "")
           $resac = db_query("insert into db_acount values($acount,416,2577,'".AddSlashes(pg_result($resaco,$conresaco,'at01_ender'))."','$this->at01_ender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_cep"]) || $this->at01_cep != "")
           $resac = db_query("insert into db_acount values($acount,416,2578,'".AddSlashes(pg_result($resaco,$conresaco,'at01_cep'))."','$this->at01_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_codver"]) || $this->at01_codver != "")
           $resac = db_query("insert into db_acount values($acount,416,7410,'".AddSlashes(pg_result($resaco,$conresaco,'at01_codver'))."','$this->at01_codver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_sigla"]) || $this->at01_sigla != "")
           $resac = db_query("insert into db_acount values($acount,416,8652,'".AddSlashes(pg_result($resaco,$conresaco,'at01_sigla'))."','$this->at01_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_ativo"]) || $this->at01_ativo != "")
           $resac = db_query("insert into db_acount values($acount,416,8987,'".AddSlashes(pg_result($resaco,$conresaco,'at01_ativo'))."','$this->at01_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_base"]) || $this->at01_base != "")
           $resac = db_query("insert into db_acount values($acount,416,10731,'".AddSlashes(pg_result($resaco,$conresaco,'at01_base'))."','$this->at01_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_obs"]) || $this->at01_obs != "")
           $resac = db_query("insert into db_acount values($acount,416,12105,'".AddSlashes(pg_result($resaco,$conresaco,'at01_obs'))."','$this->at01_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_cnpj"]) || $this->at01_cnpj != "")
           $resac = db_query("insert into db_acount values($acount,416,17067,'".AddSlashes(pg_result($resaco,$conresaco,'at01_cnpj'))."','$this->at01_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_uf"]) || $this->at01_uf != "")
           $resac = db_query("insert into db_acount values($acount,416,17068,'".AddSlashes(pg_result($resaco,$conresaco,'at01_uf'))."','$this->at01_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_telefone"]) || $this->at01_telefone != "")
           $resac = db_query("insert into db_acount values($acount,416,17069,'".AddSlashes(pg_result($resaco,$conresaco,'at01_telefone'))."','$this->at01_telefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at01_tipocliente"]) || $this->at01_tipocliente != "")
           $resac = db_query("insert into db_acount values($acount,416,17070,'".AddSlashes(pg_result($resaco,$conresaco,'at01_tipocliente'))."','$this->at01_tipocliente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Clientes nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at01_codcli;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Clientes nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at01_codcli;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at01_codcli;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at01_codcli=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at01_codcli));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2540,'$at01_codcli','E')");
         $resac = db_query("insert into db_acount values($acount,416,2540,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,2541,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_nomecli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,2543,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_site'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,2544,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,2576,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,2577,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,2578,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,7410,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_codver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,8652,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,8987,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,10731,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,12105,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,17067,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,17068,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,17069,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,416,17070,'','".AddSlashes(pg_result($resaco,$iresaco,'at01_tipocliente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from clientes
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at01_codcli != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at01_codcli = $at01_codcli ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Clientes nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at01_codcli;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Clientes nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at01_codcli;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at01_codcli;
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
        $this->erro_sql   = "Record Vazio na Tabela:clientes";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $at01_codcli=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from clientes ";
     $sql2 = "";
     if($dbwhere==""){
       if($at01_codcli!=null ){
         $sql2 .= " where clientes.at01_codcli = $at01_codcli "; 
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
   function sql_query_file ( $at01_codcli=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from clientes ";
     $sql2 = "";
     if($dbwhere==""){
       if($at01_codcli!=null ){
         $sql2 .= " where clientes.at01_codcli = $at01_codcli "; 
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