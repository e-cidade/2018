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

//MODULO: Acordos
//CLASSE DA ENTIDADE acordogrupo
class cl_acordogrupo { 
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
   var $ac02_sequencial = 0; 
   var $ac02_acordonatureza = 0; 
   var $ac02_acordotipo = 0; 
   var $ac02_descricao = null; 
   var $ac02_obs = null; 
   var $ac02_datainicial_dia = null; 
   var $ac02_datainicial_mes = null; 
   var $ac02_datainicial_ano = null; 
   var $ac02_datainicial = null; 
   var $ac02_datafinal_dia = null; 
   var $ac02_datafinal_mes = null; 
   var $ac02_datafinal_ano = null; 
   var $ac02_datafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac02_sequencial = int4 = Sequencial 
                 ac02_acordonatureza = int4 = Acordo Natureza 
                 ac02_acordotipo = int4 = Acordo Tipo 
                 ac02_descricao = varchar(100) = Descrição 
                 ac02_obs = text = Observação 
                 ac02_datainicial = date = Data Inicial 
                 ac02_datafinal = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_acordogrupo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordogrupo"); 
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
       $this->ac02_sequencial = ($this->ac02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac02_sequencial"]:$this->ac02_sequencial);
       $this->ac02_acordonatureza = ($this->ac02_acordonatureza == ""?@$GLOBALS["HTTP_POST_VARS"]["ac02_acordonatureza"]:$this->ac02_acordonatureza);
       $this->ac02_acordotipo = ($this->ac02_acordotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac02_acordotipo"]:$this->ac02_acordotipo);
       $this->ac02_descricao = ($this->ac02_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac02_descricao"]:$this->ac02_descricao);
       $this->ac02_obs = ($this->ac02_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ac02_obs"]:$this->ac02_obs);
       if($this->ac02_datainicial == ""){
         $this->ac02_datainicial_dia = ($this->ac02_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac02_datainicial_dia"]:$this->ac02_datainicial_dia);
         $this->ac02_datainicial_mes = ($this->ac02_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac02_datainicial_mes"]:$this->ac02_datainicial_mes);
         $this->ac02_datainicial_ano = ($this->ac02_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac02_datainicial_ano"]:$this->ac02_datainicial_ano);
         if($this->ac02_datainicial_dia != ""){
            $this->ac02_datainicial = $this->ac02_datainicial_ano."-".$this->ac02_datainicial_mes."-".$this->ac02_datainicial_dia;
         }
       }
       if($this->ac02_datafinal == ""){
         $this->ac02_datafinal_dia = ($this->ac02_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac02_datafinal_dia"]:$this->ac02_datafinal_dia);
         $this->ac02_datafinal_mes = ($this->ac02_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac02_datafinal_mes"]:$this->ac02_datafinal_mes);
         $this->ac02_datafinal_ano = ($this->ac02_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac02_datafinal_ano"]:$this->ac02_datafinal_ano);
         if($this->ac02_datafinal_dia != ""){
            $this->ac02_datafinal = $this->ac02_datafinal_ano."-".$this->ac02_datafinal_mes."-".$this->ac02_datafinal_dia;
         }
       }
     }else{
       $this->ac02_sequencial = ($this->ac02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac02_sequencial"]:$this->ac02_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac02_sequencial){ 
      $this->atualizacampos();
     if($this->ac02_acordonatureza == null ){ 
       $this->erro_sql = " Campo Acordo Natureza nao Informado.";
       $this->erro_campo = "ac02_acordonatureza";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac02_acordotipo == null ){ 
       $this->erro_sql = " Campo Acordo Tipo nao Informado.";
       $this->erro_campo = "ac02_acordotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac02_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ac02_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac02_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "ac02_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac02_datainicial == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "ac02_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac02_datafinal == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "ac02_datafinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac02_sequencial == "" || $ac02_sequencial == null ){
       $result = db_query("select nextval('acordogrupo_ac02_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordogrupo_ac02_sequencial_seq do campo: ac02_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac02_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordogrupo_ac02_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac02_sequencial)){
         $this->erro_sql = " Campo ac02_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac02_sequencial = $ac02_sequencial; 
       }
     }
     if(($this->ac02_sequencial == null) || ($this->ac02_sequencial == "") ){ 
       $this->erro_sql = " Campo ac02_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordogrupo(
                                       ac02_sequencial 
                                      ,ac02_acordonatureza 
                                      ,ac02_acordotipo 
                                      ,ac02_descricao 
                                      ,ac02_obs 
                                      ,ac02_datainicial 
                                      ,ac02_datafinal 
                       )
                values (
                                $this->ac02_sequencial 
                               ,$this->ac02_acordonatureza 
                               ,$this->ac02_acordotipo 
                               ,'$this->ac02_descricao' 
                               ,'$this->ac02_obs' 
                               ,".($this->ac02_datainicial == "null" || $this->ac02_datainicial == ""?"null":"'".$this->ac02_datainicial."'")." 
                               ,".($this->ac02_datafinal == "null" || $this->ac02_datafinal == ""?"null":"'".$this->ac02_datafinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Grupo ($this->ac02_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Grupo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Grupo ($this->ac02_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac02_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac02_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16085,'$this->ac02_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2820,16085,'','".AddSlashes(pg_result($resaco,0,'ac02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2820,16086,'','".AddSlashes(pg_result($resaco,0,'ac02_acordonatureza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2820,16087,'','".AddSlashes(pg_result($resaco,0,'ac02_acordotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2820,16088,'','".AddSlashes(pg_result($resaco,0,'ac02_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2820,16089,'','".AddSlashes(pg_result($resaco,0,'ac02_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2820,16090,'','".AddSlashes(pg_result($resaco,0,'ac02_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2820,16091,'','".AddSlashes(pg_result($resaco,0,'ac02_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac02_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordogrupo set ";
     $virgula = "";
     if(trim($this->ac02_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac02_sequencial"])){ 
       $sql  .= $virgula." ac02_sequencial = $this->ac02_sequencial ";
       $virgula = ",";
       if(trim($this->ac02_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac02_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac02_acordonatureza)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac02_acordonatureza"])){ 
       $sql  .= $virgula." ac02_acordonatureza = $this->ac02_acordonatureza ";
       $virgula = ",";
       if(trim($this->ac02_acordonatureza) == null ){ 
         $this->erro_sql = " Campo Acordo Natureza nao Informado.";
         $this->erro_campo = "ac02_acordonatureza";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac02_acordotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac02_acordotipo"])){ 
       $sql  .= $virgula." ac02_acordotipo = $this->ac02_acordotipo ";
       $virgula = ",";
       if(trim($this->ac02_acordotipo) == null ){ 
         $this->erro_sql = " Campo Acordo Tipo nao Informado.";
         $this->erro_campo = "ac02_acordotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac02_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac02_descricao"])){ 
       $sql  .= $virgula." ac02_descricao = '$this->ac02_descricao' ";
       $virgula = ",";
       if(trim($this->ac02_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ac02_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac02_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac02_obs"])){ 
       $sql  .= $virgula." ac02_obs = '$this->ac02_obs' ";
       $virgula = ",";
       if(trim($this->ac02_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "ac02_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac02_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac02_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac02_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ac02_datainicial = '$this->ac02_datainicial' ";
       $virgula = ",";
       if(trim($this->ac02_datainicial) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "ac02_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac02_datainicial_dia"])){ 
         $sql  .= $virgula." ac02_datainicial = null ";
         $virgula = ",";
         if(trim($this->ac02_datainicial) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "ac02_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac02_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac02_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac02_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ac02_datafinal = '$this->ac02_datafinal' ";
       $virgula = ",";
       if(trim($this->ac02_datafinal) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "ac02_datafinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac02_datafinal_dia"])){ 
         $sql  .= $virgula." ac02_datafinal = null ";
         $virgula = ",";
         if(trim($this->ac02_datafinal) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "ac02_datafinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ac02_sequencial!=null){
       $sql .= " ac02_sequencial = $this->ac02_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac02_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16085,'$this->ac02_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac02_sequencial"]) || $this->ac02_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2820,16085,'".AddSlashes(pg_result($resaco,$conresaco,'ac02_sequencial'))."','$this->ac02_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac02_acordonatureza"]) || $this->ac02_acordonatureza != "")
           $resac = db_query("insert into db_acount values($acount,2820,16086,'".AddSlashes(pg_result($resaco,$conresaco,'ac02_acordonatureza'))."','$this->ac02_acordonatureza',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac02_acordotipo"]) || $this->ac02_acordotipo != "")
           $resac = db_query("insert into db_acount values($acount,2820,16087,'".AddSlashes(pg_result($resaco,$conresaco,'ac02_acordotipo'))."','$this->ac02_acordotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac02_descricao"]) || $this->ac02_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2820,16088,'".AddSlashes(pg_result($resaco,$conresaco,'ac02_descricao'))."','$this->ac02_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac02_obs"]) || $this->ac02_obs != "")
           $resac = db_query("insert into db_acount values($acount,2820,16089,'".AddSlashes(pg_result($resaco,$conresaco,'ac02_obs'))."','$this->ac02_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac02_datainicial"]) || $this->ac02_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,2820,16090,'".AddSlashes(pg_result($resaco,$conresaco,'ac02_datainicial'))."','$this->ac02_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac02_datafinal"]) || $this->ac02_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,2820,16091,'".AddSlashes(pg_result($resaco,$conresaco,'ac02_datafinal'))."','$this->ac02_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Grupo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Grupo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac02_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac02_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16085,'$ac02_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2820,16085,'','".AddSlashes(pg_result($resaco,$iresaco,'ac02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2820,16086,'','".AddSlashes(pg_result($resaco,$iresaco,'ac02_acordonatureza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2820,16087,'','".AddSlashes(pg_result($resaco,$iresaco,'ac02_acordotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2820,16088,'','".AddSlashes(pg_result($resaco,$iresaco,'ac02_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2820,16089,'','".AddSlashes(pg_result($resaco,$iresaco,'ac02_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2820,16090,'','".AddSlashes(pg_result($resaco,$iresaco,'ac02_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2820,16091,'','".AddSlashes(pg_result($resaco,$iresaco,'ac02_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordogrupo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac02_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac02_sequencial = $ac02_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Grupo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Grupo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac02_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordogrupo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac02_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordogrupo ";
     $sql .= "      inner join acordonatureza  on  acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
     $sql .= "      inner join acordotipo  on  acordotipo.ac04_sequencial = acordogrupo.ac02_acordotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac02_sequencial!=null ){
         $sql2 .= " where acordogrupo.ac02_sequencial = $ac02_sequencial "; 
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
   function sql_query_file ( $ac02_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordogrupo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac02_sequencial!=null ){
         $sql2 .= " where acordogrupo.ac02_sequencial = $ac02_sequencial "; 
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