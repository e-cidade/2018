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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcimpactorecmov
class cl_orcimpactorecmov { 
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
   var $o69_sequen = 0; 
   var $o69_exercicio = 0; 
   var $o69_codfon = 0; 
   var $o69_codperiodo = 0; 
   var $o69_valor = 0; 
   var $o69_obs = null; 
   var $o69_proces = 0; 
   var $o69_perc = 0; 
   var $o69_codigo = 0; 
   var $o69_codimpger = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o69_sequen = int8 = Sequyencia 
                 o69_exercicio = int4 = Exercício 
                 o69_codfon = int4 = Código Fonte 
                 o69_codperiodo = int8 = Período 
                 o69_valor = float8 = Valor 
                 o69_obs = text = Obs 
                 o69_proces = int4 = Processo 
                 o69_perc = float8 = Perc. 
                 o69_codigo = int4 = Recurso 
                 o69_codimpger = int8 = Código 
                 ";
   //funcao construtor da classe 
   function cl_orcimpactorecmov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcimpactorecmov"); 
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
       $this->o69_sequen = ($this->o69_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_sequen"]:$this->o69_sequen);
       $this->o69_exercicio = ($this->o69_exercicio == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_exercicio"]:$this->o69_exercicio);
       $this->o69_codfon = ($this->o69_codfon == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_codfon"]:$this->o69_codfon);
       $this->o69_codperiodo = ($this->o69_codperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_codperiodo"]:$this->o69_codperiodo);
       $this->o69_valor = ($this->o69_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_valor"]:$this->o69_valor);
       $this->o69_obs = ($this->o69_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_obs"]:$this->o69_obs);
       $this->o69_proces = ($this->o69_proces == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_proces"]:$this->o69_proces);
       $this->o69_perc = ($this->o69_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_perc"]:$this->o69_perc);
       $this->o69_codigo = ($this->o69_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_codigo"]:$this->o69_codigo);
       $this->o69_codimpger = ($this->o69_codimpger == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_codimpger"]:$this->o69_codimpger);
     }else{
       $this->o69_sequen = ($this->o69_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["o69_sequen"]:$this->o69_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($o69_sequen){ 
      $this->atualizacampos();
     if($this->o69_exercicio == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "o69_exercicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_codfon == null ){ 
       $this->erro_sql = " Campo Código Fonte nao Informado.";
       $this->erro_campo = "o69_codfon";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_codperiodo == null ){ 
       $this->erro_sql = " Campo Período nao Informado.";
       $this->erro_campo = "o69_codperiodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o69_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_proces == null ){ 
       $this->erro_sql = " Campo Processo nao Informado.";
       $this->erro_campo = "o69_proces";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_perc == null ){ 
       $this->erro_sql = " Campo Perc. nao Informado.";
       $this->erro_campo = "o69_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_codigo == null ){ 
       $this->erro_sql = " Campo Recurso nao Informado.";
       $this->erro_campo = "o69_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o69_codimpger == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "o69_codimpger";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o69_sequen == "" || $o69_sequen == null ){
       $result = db_query("select nextval('orcimpactorecmov_o69_sequen_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcimpactorecmov_o69_sequen_seq do campo: o69_sequen"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o69_sequen = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcimpactorecmov_o69_sequen_seq");
       if(($result != false) && (pg_result($result,0,0) < $o69_sequen)){
         $this->erro_sql = " Campo o69_sequen maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o69_sequen = $o69_sequen; 
       }
     }
     if(($this->o69_sequen == null) || ($this->o69_sequen == "") ){ 
       $this->erro_sql = " Campo o69_sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcimpactorecmov(
                                       o69_sequen 
                                      ,o69_exercicio 
                                      ,o69_codfon 
                                      ,o69_codperiodo 
                                      ,o69_valor 
                                      ,o69_obs 
                                      ,o69_proces 
                                      ,o69_perc 
                                      ,o69_codigo 
                                      ,o69_codimpger 
                       )
                values (
                                $this->o69_sequen 
                               ,$this->o69_exercicio 
                               ,$this->o69_codfon 
                               ,$this->o69_codperiodo 
                               ,$this->o69_valor 
                               ,'$this->o69_obs' 
                               ,$this->o69_proces 
                               ,$this->o69_perc 
                               ,$this->o69_codigo 
                               ,$this->o69_codimpger 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Receitas ($this->o69_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Receitas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Receitas ($this->o69_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o69_sequen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o69_sequen));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6766,'$this->o69_sequen','I')");
       $resac = db_query("insert into db_acount values($acount,1104,6766,'','".AddSlashes(pg_result($resaco,0,'o69_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1104,6767,'','".AddSlashes(pg_result($resaco,0,'o69_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1104,6768,'','".AddSlashes(pg_result($resaco,0,'o69_codfon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1104,6769,'','".AddSlashes(pg_result($resaco,0,'o69_codperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1104,6770,'','".AddSlashes(pg_result($resaco,0,'o69_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1104,6771,'','".AddSlashes(pg_result($resaco,0,'o69_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1104,6772,'','".AddSlashes(pg_result($resaco,0,'o69_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1104,6773,'','".AddSlashes(pg_result($resaco,0,'o69_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1104,6782,'','".AddSlashes(pg_result($resaco,0,'o69_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1104,6784,'','".AddSlashes(pg_result($resaco,0,'o69_codimpger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o69_sequen=null) { 
      $this->atualizacampos();
     $sql = " update orcimpactorecmov set ";
     $virgula = "";
     if(trim($this->o69_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_sequen"])){ 
       $sql  .= $virgula." o69_sequen = $this->o69_sequen ";
       $virgula = ",";
       if(trim($this->o69_sequen) == null ){ 
         $this->erro_sql = " Campo Sequyencia nao Informado.";
         $this->erro_campo = "o69_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_exercicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_exercicio"])){ 
       $sql  .= $virgula." o69_exercicio = $this->o69_exercicio ";
       $virgula = ",";
       if(trim($this->o69_exercicio) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o69_exercicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_codfon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_codfon"])){ 
       $sql  .= $virgula." o69_codfon = $this->o69_codfon ";
       $virgula = ",";
       if(trim($this->o69_codfon) == null ){ 
         $this->erro_sql = " Campo Código Fonte nao Informado.";
         $this->erro_campo = "o69_codfon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_codperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_codperiodo"])){ 
       $sql  .= $virgula." o69_codperiodo = $this->o69_codperiodo ";
       $virgula = ",";
       if(trim($this->o69_codperiodo) == null ){ 
         $this->erro_sql = " Campo Período nao Informado.";
         $this->erro_campo = "o69_codperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_valor"])){ 
       $sql  .= $virgula." o69_valor = $this->o69_valor ";
       $virgula = ",";
       if(trim($this->o69_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o69_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_obs"])){ 
       $sql  .= $virgula." o69_obs = '$this->o69_obs' ";
       $virgula = ",";
     }
     if(trim($this->o69_proces)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_proces"])){ 
       $sql  .= $virgula." o69_proces = $this->o69_proces ";
       $virgula = ",";
       if(trim($this->o69_proces) == null ){ 
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "o69_proces";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_perc"])){ 
       $sql  .= $virgula." o69_perc = $this->o69_perc ";
       $virgula = ",";
       if(trim($this->o69_perc) == null ){ 
         $this->erro_sql = " Campo Perc. nao Informado.";
         $this->erro_campo = "o69_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_codigo"])){ 
       $sql  .= $virgula." o69_codigo = $this->o69_codigo ";
       $virgula = ",";
       if(trim($this->o69_codigo) == null ){ 
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "o69_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o69_codimpger)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o69_codimpger"])){ 
       $sql  .= $virgula." o69_codimpger = $this->o69_codimpger ";
       $virgula = ",";
       if(trim($this->o69_codimpger) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o69_codimpger";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o69_sequen!=null){
       $sql .= " o69_sequen = $this->o69_sequen";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o69_sequen));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6766,'$this->o69_sequen','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_sequen"]))
           $resac = db_query("insert into db_acount values($acount,1104,6766,'".AddSlashes(pg_result($resaco,$conresaco,'o69_sequen'))."','$this->o69_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_exercicio"]))
           $resac = db_query("insert into db_acount values($acount,1104,6767,'".AddSlashes(pg_result($resaco,$conresaco,'o69_exercicio'))."','$this->o69_exercicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_codfon"]))
           $resac = db_query("insert into db_acount values($acount,1104,6768,'".AddSlashes(pg_result($resaco,$conresaco,'o69_codfon'))."','$this->o69_codfon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_codperiodo"]))
           $resac = db_query("insert into db_acount values($acount,1104,6769,'".AddSlashes(pg_result($resaco,$conresaco,'o69_codperiodo'))."','$this->o69_codperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_valor"]))
           $resac = db_query("insert into db_acount values($acount,1104,6770,'".AddSlashes(pg_result($resaco,$conresaco,'o69_valor'))."','$this->o69_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_obs"]))
           $resac = db_query("insert into db_acount values($acount,1104,6771,'".AddSlashes(pg_result($resaco,$conresaco,'o69_obs'))."','$this->o69_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_proces"]))
           $resac = db_query("insert into db_acount values($acount,1104,6772,'".AddSlashes(pg_result($resaco,$conresaco,'o69_proces'))."','$this->o69_proces',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_perc"]))
           $resac = db_query("insert into db_acount values($acount,1104,6773,'".AddSlashes(pg_result($resaco,$conresaco,'o69_perc'))."','$this->o69_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1104,6782,'".AddSlashes(pg_result($resaco,$conresaco,'o69_codigo'))."','$this->o69_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o69_codimpger"]))
           $resac = db_query("insert into db_acount values($acount,1104,6784,'".AddSlashes(pg_result($resaco,$conresaco,'o69_codimpger'))."','$this->o69_codimpger',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o69_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o69_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o69_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o69_sequen=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o69_sequen));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6766,'$o69_sequen','E')");
         $resac = db_query("insert into db_acount values($acount,1104,6766,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1104,6767,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1104,6768,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_codfon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1104,6769,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_codperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1104,6770,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1104,6771,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1104,6772,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1104,6773,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1104,6782,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1104,6784,'','".AddSlashes(pg_result($resaco,$iresaco,'o69_codimpger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcimpactorecmov
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o69_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o69_sequen = $o69_sequen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o69_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o69_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o69_sequen;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcimpactorecmov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query_prorel ( $o69_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactorecmov ";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcimpactorecmov.o69_codfon and orcfontes.o57_codfon = ".db_getsession("DB_anousu");
     $sql .= "      inner join orcimpactoperiodo  on  orcimpactoperiodo.o96_codperiodo = orcimpactorecmov.o69_codperiodo";
     $sql2 = "";
     if($dbwhere==""){
       if($o69_sequen!=null ){
         $sql2 .= " where orcimpactorecmov.o69_sequen = $o69_sequen "; 
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
   function sql_query ( $o69_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactorecmov ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcimpactorecmov.o69_codigo";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcimpactorecmov.o69_codfon and orcfontes.o57_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join orcimpactoperiodo  on  orcimpactoperiodo.o96_codperiodo = orcimpactorecmov.o69_codperiodo";
     $sql .= "      inner join orcimpactoger  on  orcimpactoger.o62_codimpger = orcimpactorecmov.o69_codimpger";
     $sql2 = "";
     if($dbwhere==""){
       if($o69_sequen!=null ){
         $sql2 .= " where orcimpactorecmov.o69_sequen = $o69_sequen "; 
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

   function sql_query_file ( $o69_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactorecmov ";
     $sql2 = "";
     if($dbwhere==""){
       if($o69_sequen!=null ){
         $sql2 .= " where orcimpactorecmov.o69_sequen = $o69_sequen "; 
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
   function sql_query_compl ( $o69_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcimpactorecmov ";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon      = orcimpactorecmov.o69_codfon and orcfontes.o57_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo    = orcimpactorecmov.o69_codigo";
     $sql .= "      inner join orcimpactoger  on  orcimpactoger.o62_codimpger    = orcimpactorecmov.o69_codimpger";
     $sql .= "      inner join orcimpactoperiodo  on  orcimpactoperiodo.o96_codperiodo = orcimpactorecmov.o69_codperiodo";
     $sql2 = "";
     if($dbwhere==""){
       if($o69_sequen!=null ){
         $sql2 .= " where orcimpactorecmov.o69_sequen = $o69_sequen ";
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