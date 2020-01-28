<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: acordos
//CLASSE DA ENTIDADE acordoposicaoperiodo
class cl_acordoposicaoperiodo { 
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
   var $ac36_sequencial = 0; 
   var $ac36_acordoposicao = 0; 
   var $ac36_datainicial_dia = null; 
   var $ac36_datainicial_mes = null; 
   var $ac36_datainicial_ano = null; 
   var $ac36_datainicial = null; 
   var $ac36_datafinal_dia = null; 
   var $ac36_datafinal_mes = null; 
   var $ac36_datafinal_ano = null; 
   var $ac36_datafinal = null; 
   var $ac36_descricao = null; 
   var $ac36_numero = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac36_sequencial = int4 = Codigo_sequencial 
                 ac36_acordoposicao = int4 = Acordo 
                 ac36_datainicial = date = Data inicial 
                 ac36_datafinal = date = Data final 
                 ac36_descricao = varchar(50) = Descricão 
                 ac36_numero = int4 = Numero 
                 ";
   //funcao construtor da classe 
   function cl_acordoposicaoperiodo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoposicaoperiodo"); 
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
       $this->ac36_sequencial = ($this->ac36_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac36_sequencial"]:$this->ac36_sequencial);
       $this->ac36_acordoposicao = ($this->ac36_acordoposicao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac36_acordoposicao"]:$this->ac36_acordoposicao);
       if($this->ac36_datainicial == ""){
         $this->ac36_datainicial_dia = ($this->ac36_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac36_datainicial_dia"]:$this->ac36_datainicial_dia);
         $this->ac36_datainicial_mes = ($this->ac36_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac36_datainicial_mes"]:$this->ac36_datainicial_mes);
         $this->ac36_datainicial_ano = ($this->ac36_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac36_datainicial_ano"]:$this->ac36_datainicial_ano);
         if($this->ac36_datainicial_dia != ""){
            $this->ac36_datainicial = $this->ac36_datainicial_ano."-".$this->ac36_datainicial_mes."-".$this->ac36_datainicial_dia;
         }
       }
       if($this->ac36_datafinal == ""){
         $this->ac36_datafinal_dia = ($this->ac36_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac36_datafinal_dia"]:$this->ac36_datafinal_dia);
         $this->ac36_datafinal_mes = ($this->ac36_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac36_datafinal_mes"]:$this->ac36_datafinal_mes);
         $this->ac36_datafinal_ano = ($this->ac36_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac36_datafinal_ano"]:$this->ac36_datafinal_ano);
         if($this->ac36_datafinal_dia != ""){
            $this->ac36_datafinal = $this->ac36_datafinal_ano."-".$this->ac36_datafinal_mes."-".$this->ac36_datafinal_dia;
         }
       }
       $this->ac36_descricao = ($this->ac36_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac36_descricao"]:$this->ac36_descricao);
       $this->ac36_numero = ($this->ac36_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ac36_numero"]:$this->ac36_numero);
     }else{
       $this->ac36_sequencial = ($this->ac36_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac36_sequencial"]:$this->ac36_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac36_sequencial){ 
      $this->atualizacampos();
     if($this->ac36_acordoposicao == null ){ 
       $this->erro_sql = " Campo Acordo nao Informado.";
       $this->erro_campo = "ac36_acordoposicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac36_datainicial == null ){ 
       $this->erro_sql = " Campo Data inicial nao Informado.";
       $this->erro_campo = "ac36_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac36_datafinal == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "ac36_datafinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac36_descricao == null ){ 
       $this->erro_sql = " Campo Descricão nao Informado.";
       $this->erro_campo = "ac36_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac36_numero == null ){ 
       $this->erro_sql = " Campo Numero nao Informado.";
       $this->erro_campo = "ac36_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac36_sequencial == "" || $ac36_sequencial == null ){
       $result = db_query("select nextval('acordoposicaoperiodo_ac36_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoposicaoperiodo_ac36_sequencial_seq do campo: ac36_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac36_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoposicaoperiodo_ac36_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac36_sequencial)){
         $this->erro_sql = " Campo ac36_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac36_sequencial = $ac36_sequencial; 
       }
     }
     if(($this->ac36_sequencial == null) || ($this->ac36_sequencial == "") ){ 
       $this->erro_sql = " Campo ac36_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoposicaoperiodo(
                                       ac36_sequencial 
                                      ,ac36_acordoposicao 
                                      ,ac36_datainicial 
                                      ,ac36_datafinal 
                                      ,ac36_descricao 
                                      ,ac36_numero 
                       )
                values (
                                $this->ac36_sequencial 
                               ,$this->ac36_acordoposicao 
                               ,".($this->ac36_datainicial == "null" || $this->ac36_datainicial == ""?"null":"'".$this->ac36_datainicial."'")." 
                               ,".($this->ac36_datafinal == "null" || $this->ac36_datafinal == ""?"null":"'".$this->ac36_datafinal."'")." 
                               ,'$this->ac36_descricao' 
                               ,$this->ac36_numero 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "acordoposicaoperiodo ($this->ac36_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "acordoposicaoperiodo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "acordoposicaoperiodo ($this->ac36_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac36_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac36_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18035,'$this->ac36_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3187,18035,'','".AddSlashes(pg_result($resaco,0,'ac36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3187,18036,'','".AddSlashes(pg_result($resaco,0,'ac36_acordoposicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3187,18037,'','".AddSlashes(pg_result($resaco,0,'ac36_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3187,18038,'','".AddSlashes(pg_result($resaco,0,'ac36_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3187,18039,'','".AddSlashes(pg_result($resaco,0,'ac36_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3187,18040,'','".AddSlashes(pg_result($resaco,0,'ac36_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac36_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoposicaoperiodo set ";
     $virgula = "";
     if(trim($this->ac36_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac36_sequencial"])){ 
       $sql  .= $virgula." ac36_sequencial = $this->ac36_sequencial ";
       $virgula = ",";
       if(trim($this->ac36_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo_sequencial nao Informado.";
         $this->erro_campo = "ac36_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac36_acordoposicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac36_acordoposicao"])){ 
       $sql  .= $virgula." ac36_acordoposicao = $this->ac36_acordoposicao ";
       $virgula = ",";
       if(trim($this->ac36_acordoposicao) == null ){ 
         $this->erro_sql = " Campo Acordo nao Informado.";
         $this->erro_campo = "ac36_acordoposicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac36_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac36_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac36_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ac36_datainicial = '$this->ac36_datainicial' ";
       $virgula = ",";
       if(trim($this->ac36_datainicial) == null ){ 
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "ac36_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac36_datainicial_dia"])){ 
         $sql  .= $virgula." ac36_datainicial = null ";
         $virgula = ",";
         if(trim($this->ac36_datainicial) == null ){ 
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "ac36_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac36_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac36_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac36_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ac36_datafinal = '$this->ac36_datafinal' ";
       $virgula = ",";
       if(trim($this->ac36_datafinal) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "ac36_datafinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac36_datafinal_dia"])){ 
         $sql  .= $virgula." ac36_datafinal = null ";
         $virgula = ",";
         if(trim($this->ac36_datafinal) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "ac36_datafinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac36_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac36_descricao"])){ 
       $sql  .= $virgula." ac36_descricao = '$this->ac36_descricao' ";
       $virgula = ",";
       if(trim($this->ac36_descricao) == null ){ 
         $this->erro_sql = " Campo Descricão nao Informado.";
         $this->erro_campo = "ac36_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac36_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac36_numero"])){ 
       $sql  .= $virgula." ac36_numero = $this->ac36_numero ";
       $virgula = ",";
       if(trim($this->ac36_numero) == null ){ 
         $this->erro_sql = " Campo Numero nao Informado.";
         $this->erro_campo = "ac36_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac36_sequencial!=null){
       $sql .= " ac36_sequencial = $this->ac36_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac36_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18035,'$this->ac36_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac36_sequencial"]) || $this->ac36_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3187,18035,'".AddSlashes(pg_result($resaco,$conresaco,'ac36_sequencial'))."','$this->ac36_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac36_acordoposicao"]) || $this->ac36_acordoposicao != "")
           $resac = db_query("insert into db_acount values($acount,3187,18036,'".AddSlashes(pg_result($resaco,$conresaco,'ac36_acordoposicao'))."','$this->ac36_acordoposicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac36_datainicial"]) || $this->ac36_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,3187,18037,'".AddSlashes(pg_result($resaco,$conresaco,'ac36_datainicial'))."','$this->ac36_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac36_datafinal"]) || $this->ac36_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,3187,18038,'".AddSlashes(pg_result($resaco,$conresaco,'ac36_datafinal'))."','$this->ac36_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac36_descricao"]) || $this->ac36_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3187,18039,'".AddSlashes(pg_result($resaco,$conresaco,'ac36_descricao'))."','$this->ac36_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac36_numero"]) || $this->ac36_numero != "")
           $resac = db_query("insert into db_acount values($acount,3187,18040,'".AddSlashes(pg_result($resaco,$conresaco,'ac36_numero'))."','$this->ac36_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "acordoposicaoperiodo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac36_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "acordoposicaoperiodo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac36_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac36_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18035,'$ac36_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3187,18035,'','".AddSlashes(pg_result($resaco,$iresaco,'ac36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3187,18036,'','".AddSlashes(pg_result($resaco,$iresaco,'ac36_acordoposicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3187,18037,'','".AddSlashes(pg_result($resaco,$iresaco,'ac36_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3187,18038,'','".AddSlashes(pg_result($resaco,$iresaco,'ac36_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3187,18039,'','".AddSlashes(pg_result($resaco,$iresaco,'ac36_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3187,18040,'','".AddSlashes(pg_result($resaco,$iresaco,'ac36_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoposicaoperiodo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac36_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac36_sequencial = $ac36_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "acordoposicaoperiodo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac36_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "acordoposicaoperiodo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac36_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoposicaoperiodo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoposicaoperiodo ";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoposicaoperiodo.ac36_acordoposicao";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoposicao.ac26_acordo";
     $sql .= "      inner join acordoposicaotipo  on  acordoposicaotipo.ac27_sequencial = acordoposicao.ac26_acordoposicaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac36_sequencial!=null ){
         $sql2 .= " where acordoposicaoperiodo.ac36_sequencial = $ac36_sequencial "; 
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
   function sql_query_file ( $ac36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoposicaoperiodo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac36_sequencial!=null ){
         $sql2 .= " where acordoposicaoperiodo.ac36_sequencial = $ac36_sequencial "; 
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