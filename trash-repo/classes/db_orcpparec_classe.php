<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE orcpparec
class cl_orcpparec { 
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
   var $o27_sequen = 0; 
   var $o27_exercicio = 0; 
   var $o27_codfon = 0; 
   var $o27_codleippa = 0; 
   var $o27_valor = 0; 
   var $o27_obs = null; 
   var $o27_proces = 0; 
   var $o27_perc = 0; 
   var $o27_concarpeculiar = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o27_sequen = int8 = Sequencial 
                 o27_exercicio = int8 = Exercício 
                 o27_codfon = int4 = Código Fonte 
                 o27_codleippa = int8 = Código 
                 o27_valor = float8 = Valor 
                 o27_obs = text = Observação 
                 o27_proces = int8 = Processo 
                 o27_perc = float4 = Perc 
                 o27_concarpeculiar = varchar(100) = Caracteristica Peculiar 
                 ";
   //funcao construtor da classe 
   function cl_orcpparec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcpparec"); 
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
       $this->o27_sequen = ($this->o27_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["o27_sequen"]:$this->o27_sequen);
       $this->o27_exercicio = ($this->o27_exercicio == ""?@$GLOBALS["HTTP_POST_VARS"]["o27_exercicio"]:$this->o27_exercicio);
       $this->o27_codfon = ($this->o27_codfon == ""?@$GLOBALS["HTTP_POST_VARS"]["o27_codfon"]:$this->o27_codfon);
       $this->o27_codleippa = ($this->o27_codleippa == ""?@$GLOBALS["HTTP_POST_VARS"]["o27_codleippa"]:$this->o27_codleippa);
       $this->o27_valor = ($this->o27_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o27_valor"]:$this->o27_valor);
       $this->o27_obs = ($this->o27_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["o27_obs"]:$this->o27_obs);
       $this->o27_proces = ($this->o27_proces == ""?@$GLOBALS["HTTP_POST_VARS"]["o27_proces"]:$this->o27_proces);
       $this->o27_perc = ($this->o27_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["o27_perc"]:$this->o27_perc);
       $this->o27_concarpeculiar = ($this->o27_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["o27_concarpeculiar"]:$this->o27_concarpeculiar);
     }else{
       $this->o27_sequen = ($this->o27_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["o27_sequen"]:$this->o27_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($o27_sequen){ 
      $this->atualizacampos();
     if($this->o27_exercicio == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "o27_exercicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o27_codfon == null ){ 
       $this->erro_sql = " Campo Código Fonte nao Informado.";
       $this->erro_campo = "o27_codfon";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o27_codleippa == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "o27_codleippa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o27_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o27_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o27_proces == null ){ 
       $this->erro_sql = " Campo Processo nao Informado.";
       $this->erro_campo = "o27_proces";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o27_perc == null ){ 
       $this->erro_sql = " Campo Perc nao Informado.";
       $this->erro_campo = "o27_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o27_concarpeculiar == null ){ 
       $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
       $this->erro_campo = "o27_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o27_sequen == "" || $o27_sequen == null ){
       $result = db_query("select nextval('orcpparec_o27_sequen_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcpparec_o27_sequen_seq do campo: o27_sequen"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o27_sequen = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcpparec_o27_sequen_seq");
       if(($result != false) && (pg_result($result,0,0) < $o27_sequen)){
         $this->erro_sql = " Campo o27_sequen maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o27_sequen = $o27_sequen; 
       }
     }
     if(($this->o27_sequen == null) || ($this->o27_sequen == "") ){ 
       $this->erro_sql = " Campo o27_sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcpparec(
                                       o27_sequen 
                                      ,o27_exercicio 
                                      ,o27_codfon 
                                      ,o27_codleippa 
                                      ,o27_valor 
                                      ,o27_obs 
                                      ,o27_proces 
                                      ,o27_perc 
                                      ,o27_concarpeculiar 
                       )
                values (
                                $this->o27_sequen 
                               ,$this->o27_exercicio 
                               ,$this->o27_codfon 
                               ,$this->o27_codleippa 
                               ,$this->o27_valor 
                               ,'$this->o27_obs' 
                               ,$this->o27_proces 
                               ,$this->o27_perc 
                               ,'$this->o27_concarpeculiar' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Receitas ($this->o27_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Receitas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Receitas ($this->o27_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o27_sequen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o27_sequen));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6557,'$this->o27_sequen','I')");
       $resac = db_query("insert into db_acount values($acount,1079,6557,'','".AddSlashes(pg_result($resaco,0,'o27_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1079,6552,'','".AddSlashes(pg_result($resaco,0,'o27_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1079,6553,'','".AddSlashes(pg_result($resaco,0,'o27_codfon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1079,6556,'','".AddSlashes(pg_result($resaco,0,'o27_codleippa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1079,6554,'','".AddSlashes(pg_result($resaco,0,'o27_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1079,6555,'','".AddSlashes(pg_result($resaco,0,'o27_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1079,6558,'','".AddSlashes(pg_result($resaco,0,'o27_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1079,6594,'','".AddSlashes(pg_result($resaco,0,'o27_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1079,10819,'','".AddSlashes(pg_result($resaco,0,'o27_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o27_sequen=null) { 
      $this->atualizacampos();
     $sql = " update orcpparec set ";
     $virgula = "";
     if(trim($this->o27_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o27_sequen"])){ 
       $sql  .= $virgula." o27_sequen = $this->o27_sequen ";
       $virgula = ",";
       if(trim($this->o27_sequen) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o27_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o27_exercicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o27_exercicio"])){ 
       $sql  .= $virgula." o27_exercicio = $this->o27_exercicio ";
       $virgula = ",";
       if(trim($this->o27_exercicio) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o27_exercicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o27_codfon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o27_codfon"])){ 
       $sql  .= $virgula." o27_codfon = $this->o27_codfon ";
       $virgula = ",";
       if(trim($this->o27_codfon) == null ){ 
         $this->erro_sql = " Campo Código Fonte nao Informado.";
         $this->erro_campo = "o27_codfon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o27_codleippa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o27_codleippa"])){ 
       $sql  .= $virgula." o27_codleippa = $this->o27_codleippa ";
       $virgula = ",";
       if(trim($this->o27_codleippa) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o27_codleippa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o27_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o27_valor"])){ 
       $sql  .= $virgula." o27_valor = $this->o27_valor ";
       $virgula = ",";
       if(trim($this->o27_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o27_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o27_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o27_obs"])){ 
       $sql  .= $virgula." o27_obs = '$this->o27_obs' ";
       $virgula = ",";
     }
     if(trim($this->o27_proces)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o27_proces"])){ 
       $sql  .= $virgula." o27_proces = $this->o27_proces ";
       $virgula = ",";
       if(trim($this->o27_proces) == null ){ 
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "o27_proces";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o27_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o27_perc"])){ 
       $sql  .= $virgula." o27_perc = $this->o27_perc ";
       $virgula = ",";
       if(trim($this->o27_perc) == null ){ 
         $this->erro_sql = " Campo Perc nao Informado.";
         $this->erro_campo = "o27_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o27_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o27_concarpeculiar"])){ 
       $sql  .= $virgula." o27_concarpeculiar = '$this->o27_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->o27_concarpeculiar) == null ){ 
         $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
         $this->erro_campo = "o27_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o27_sequen!=null){
       $sql .= " o27_sequen = $this->o27_sequen";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o27_sequen));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6557,'$this->o27_sequen','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o27_sequen"]) || $this->o27_sequen != "")
           $resac = db_query("insert into db_acount values($acount,1079,6557,'".AddSlashes(pg_result($resaco,$conresaco,'o27_sequen'))."','$this->o27_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o27_exercicio"]) || $this->o27_exercicio != "")
           $resac = db_query("insert into db_acount values($acount,1079,6552,'".AddSlashes(pg_result($resaco,$conresaco,'o27_exercicio'))."','$this->o27_exercicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o27_codfon"]) || $this->o27_codfon != "")
           $resac = db_query("insert into db_acount values($acount,1079,6553,'".AddSlashes(pg_result($resaco,$conresaco,'o27_codfon'))."','$this->o27_codfon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o27_codleippa"]) || $this->o27_codleippa != "")
           $resac = db_query("insert into db_acount values($acount,1079,6556,'".AddSlashes(pg_result($resaco,$conresaco,'o27_codleippa'))."','$this->o27_codleippa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o27_valor"]) || $this->o27_valor != "")
           $resac = db_query("insert into db_acount values($acount,1079,6554,'".AddSlashes(pg_result($resaco,$conresaco,'o27_valor'))."','$this->o27_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o27_obs"]) || $this->o27_obs != "")
           $resac = db_query("insert into db_acount values($acount,1079,6555,'".AddSlashes(pg_result($resaco,$conresaco,'o27_obs'))."','$this->o27_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o27_proces"]) || $this->o27_proces != "")
           $resac = db_query("insert into db_acount values($acount,1079,6558,'".AddSlashes(pg_result($resaco,$conresaco,'o27_proces'))."','$this->o27_proces',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o27_perc"]) || $this->o27_perc != "")
           $resac = db_query("insert into db_acount values($acount,1079,6594,'".AddSlashes(pg_result($resaco,$conresaco,'o27_perc'))."','$this->o27_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o27_concarpeculiar"]) || $this->o27_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,1079,10819,'".AddSlashes(pg_result($resaco,$conresaco,'o27_concarpeculiar'))."','$this->o27_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o27_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o27_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o27_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o27_sequen=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o27_sequen));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6557,'$o27_sequen','E')");
         $resac = db_query("insert into db_acount values($acount,1079,6557,'','".AddSlashes(pg_result($resaco,$iresaco,'o27_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1079,6552,'','".AddSlashes(pg_result($resaco,$iresaco,'o27_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1079,6553,'','".AddSlashes(pg_result($resaco,$iresaco,'o27_codfon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1079,6556,'','".AddSlashes(pg_result($resaco,$iresaco,'o27_codleippa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1079,6554,'','".AddSlashes(pg_result($resaco,$iresaco,'o27_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1079,6555,'','".AddSlashes(pg_result($resaco,$iresaco,'o27_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1079,6558,'','".AddSlashes(pg_result($resaco,$iresaco,'o27_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1079,6594,'','".AddSlashes(pg_result($resaco,$iresaco,'o27_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1079,10819,'','".AddSlashes(pg_result($resaco,$iresaco,'o27_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcpparec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o27_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o27_sequen = $o27_sequen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o27_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o27_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o27_sequen;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcpparec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o27_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcpparec ";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcpparec.o27_codfon and  orcfontes.o57_anousu = orcpparec.o27_exercicio";
     $sql .= "      inner join orcppalei  on  orcppalei.o21_codleippa = orcpparec.o27_codleippa";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = orcpparec.o27_concarpeculiar";
     $sql2 = "";
     if($dbwhere==""){
       if($o27_sequen!=null ){
         $sql2 .= " where orcpparec.o27_sequen = $o27_sequen "; 
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
   function sql_query_file ( $o27_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcpparec ";
     $sql2 = "";
     if($dbwhere==""){
       if($o27_sequen!=null ){
         $sql2 .= " where orcpparec.o27_sequen = $o27_sequen "; 
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
   function sql_query_compl ( $o27_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcpparec ";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon    = orcpparec.o27_codfon  and o57_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join orcppalei  on  orcppalei.o21_codleippa = orcpparec.o27_codleippa";
     $sql2 = "";
     if($dbwhere==""){
       if($o27_sequen!=null ){
         $sql2 .= " where orcpparec.o27_sequen = $o27_sequen ";
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