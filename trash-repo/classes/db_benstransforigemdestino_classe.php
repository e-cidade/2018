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

//MODULO: patrimonio
//CLASSE DA ENTIDADE benstransforigemdestino
class cl_benstransforigemdestino { 
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
   var $t34_sequencial = 0; 
   var $t34_transferencia = 0; 
   var $t34_bem = 0; 
   var $t34_divisaoorigem = null; 
   var $t34_divisaodestino = null; 
   var $t34_departamentodestino = 0; 
   var $t34_departamentoorigem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t34_sequencial = int4 = sequencia de divisão de origem e destino 
                 t34_transferencia = int4 = sequencial da transferencia do bem 
                 t34_bem = int4 = sequencial do bem na transferencia 
                 t34_divisaoorigem = int4 = sequencial da divisao de origem 
                 t34_divisaodestino = int4 = Sequencial da divisão de destino 
                 t34_departamentodestino = float4 = Departamento Atual 
                 t34_departamentoorigem = int4 = Departamento Origem 
                 ";
   //funcao construtor da classe 
   function cl_benstransforigemdestino() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benstransforigemdestino"); 
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
       $this->t34_sequencial = ($this->t34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t34_sequencial"]:$this->t34_sequencial);
       $this->t34_transferencia = ($this->t34_transferencia == ""?@$GLOBALS["HTTP_POST_VARS"]["t34_transferencia"]:$this->t34_transferencia);
       $this->t34_bem = ($this->t34_bem == ""?@$GLOBALS["HTTP_POST_VARS"]["t34_bem"]:$this->t34_bem);
       $this->t34_divisaoorigem = ($this->t34_divisaoorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["t34_divisaoorigem"]:$this->t34_divisaoorigem);
       $this->t34_divisaodestino = ($this->t34_divisaodestino == ""?@$GLOBALS["HTTP_POST_VARS"]["t34_divisaodestino"]:$this->t34_divisaodestino);
       $this->t34_departamentodestino = ($this->t34_departamentodestino == ""?@$GLOBALS["HTTP_POST_VARS"]["t34_departamentodestino"]:$this->t34_departamentodestino);
       $this->t34_departamentoorigem = ($this->t34_departamentoorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["t34_departamentoorigem"]:$this->t34_departamentoorigem);
     }else{
       $this->t34_sequencial = ($this->t34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t34_sequencial"]:$this->t34_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t34_sequencial){ 
      $this->atualizacampos();
     if($this->t34_transferencia == null ){ 
       $this->erro_sql = " Campo sequencial da transferencia do bem nao Informado.";
       $this->erro_campo = "t34_transferencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t34_bem == null ){ 
       $this->erro_sql = " Campo sequencial do bem na transferencia nao Informado.";
       $this->erro_campo = "t34_bem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t34_divisaoorigem == null ){ 
       $this->t34_divisaoorigem = "null";
     }
     if($this->t34_divisaodestino == null ){ 
       $this->t34_divisaodestino = "null";
     }
     if($this->t34_departamentodestino == null ){ 
       $this->erro_sql = " Campo Departamento Atual nao Informado.";
       $this->erro_campo = "t34_departamentodestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t34_departamentoorigem == null ){ 
       $this->erro_sql = " Campo Departamento Origem nao Informado.";
       $this->erro_campo = "t34_departamentoorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t34_sequencial == "" || $t34_sequencial == null ){
       $result = db_query("select nextval('benstransforigemdestino_t34_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: benstransforigemdestino_t34_sequencial_seq do campo: t34_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t34_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from benstransforigemdestino_t34_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t34_sequencial)){
         $this->erro_sql = " Campo t34_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t34_sequencial = $t34_sequencial; 
       }
     }
     if(($this->t34_sequencial == null) || ($this->t34_sequencial == "") ){ 
       $this->erro_sql = " Campo t34_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into benstransforigemdestino(
                                       t34_sequencial 
                                      ,t34_transferencia 
                                      ,t34_bem 
                                      ,t34_divisaoorigem 
                                      ,t34_divisaodestino 
                                      ,t34_departamentodestino 
                                      ,t34_departamentoorigem 
                       )
                values (
                                $this->t34_sequencial 
                               ,$this->t34_transferencia 
                               ,$this->t34_bem 
                               ,$this->t34_divisaoorigem 
                               ,$this->t34_divisaodestino 
                               ,$this->t34_departamentodestino 
                               ,$this->t34_departamentoorigem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "registrar divisão de origem e destino ($this->t34_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "registrar divisão de origem e destino já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "registrar divisão de origem e destino ($this->t34_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t34_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t34_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19305,'$this->t34_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3432,19305,'','".AddSlashes(pg_result($resaco,0,'t34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3432,19306,'','".AddSlashes(pg_result($resaco,0,'t34_transferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3432,19307,'','".AddSlashes(pg_result($resaco,0,'t34_bem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3432,19308,'','".AddSlashes(pg_result($resaco,0,'t34_divisaoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3432,19309,'','".AddSlashes(pg_result($resaco,0,'t34_divisaodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3432,19399,'','".AddSlashes(pg_result($resaco,0,'t34_departamentodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3432,19398,'','".AddSlashes(pg_result($resaco,0,'t34_departamentoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t34_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update benstransforigemdestino set ";
     $virgula = "";
     if(trim($this->t34_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t34_sequencial"])){ 
       $sql  .= $virgula." t34_sequencial = $this->t34_sequencial ";
       $virgula = ",";
       if(trim($this->t34_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencia de divisão de origem e destino nao Informado.";
         $this->erro_campo = "t34_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t34_transferencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t34_transferencia"])){ 
       $sql  .= $virgula." t34_transferencia = $this->t34_transferencia ";
       $virgula = ",";
       if(trim($this->t34_transferencia) == null ){ 
         $this->erro_sql = " Campo sequencial da transferencia do bem nao Informado.";
         $this->erro_campo = "t34_transferencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t34_bem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t34_bem"])){ 
       $sql  .= $virgula." t34_bem = $this->t34_bem ";
       $virgula = ",";
       if(trim($this->t34_bem) == null ){ 
         $this->erro_sql = " Campo sequencial do bem na transferencia nao Informado.";
         $this->erro_campo = "t34_bem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t34_divisaoorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t34_divisaoorigem"])){ 
        if(trim($this->t34_divisaoorigem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t34_divisaoorigem"])){ 
           $this->t34_divisaoorigem = "null" ; 
        } 
       $sql  .= $virgula." t34_divisaoorigem = $this->t34_divisaoorigem ";
       $virgula = ",";
     }
     if(trim($this->t34_divisaodestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t34_divisaodestino"])){ 
        if(trim($this->t34_divisaodestino)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t34_divisaodestino"])){ 
           $this->t34_divisaodestino = "null" ; 
        } 
       $sql  .= $virgula." t34_divisaodestino = $this->t34_divisaodestino ";
       $virgula = ",";
     }
     if(trim($this->t34_departamentodestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t34_departamentodestino"])){ 
       $sql  .= $virgula." t34_departamentodestino = $this->t34_departamentodestino ";
       $virgula = ",";
       if(trim($this->t34_departamentodestino) == null ){ 
         $this->erro_sql = " Campo Departamento Atual nao Informado.";
         $this->erro_campo = "t34_departamentodestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t34_departamentoorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t34_departamentoorigem"])){ 
       $sql  .= $virgula." t34_departamentoorigem = $this->t34_departamentoorigem ";
       $virgula = ",";
       if(trim($this->t34_departamentoorigem) == null ){ 
         $this->erro_sql = " Campo Departamento Origem nao Informado.";
         $this->erro_campo = "t34_departamentoorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t34_sequencial!=null){
       $sql .= " t34_sequencial = $this->t34_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t34_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19305,'$this->t34_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t34_sequencial"]) || $this->t34_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3432,19305,'".AddSlashes(pg_result($resaco,$conresaco,'t34_sequencial'))."','$this->t34_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t34_transferencia"]) || $this->t34_transferencia != "")
           $resac = db_query("insert into db_acount values($acount,3432,19306,'".AddSlashes(pg_result($resaco,$conresaco,'t34_transferencia'))."','$this->t34_transferencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t34_bem"]) || $this->t34_bem != "")
           $resac = db_query("insert into db_acount values($acount,3432,19307,'".AddSlashes(pg_result($resaco,$conresaco,'t34_bem'))."','$this->t34_bem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t34_divisaoorigem"]) || $this->t34_divisaoorigem != "")
           $resac = db_query("insert into db_acount values($acount,3432,19308,'".AddSlashes(pg_result($resaco,$conresaco,'t34_divisaoorigem'))."','$this->t34_divisaoorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t34_divisaodestino"]) || $this->t34_divisaodestino != "")
           $resac = db_query("insert into db_acount values($acount,3432,19309,'".AddSlashes(pg_result($resaco,$conresaco,'t34_divisaodestino'))."','$this->t34_divisaodestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t34_departamentodestino"]) || $this->t34_departamentodestino != "")
           $resac = db_query("insert into db_acount values($acount,3432,19399,'".AddSlashes(pg_result($resaco,$conresaco,'t34_departamentodestino'))."','$this->t34_departamentodestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t34_departamentoorigem"]) || $this->t34_departamentoorigem != "")
           $resac = db_query("insert into db_acount values($acount,3432,19398,'".AddSlashes(pg_result($resaco,$conresaco,'t34_departamentoorigem'))."','$this->t34_departamentoorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "registrar divisão de origem e destino nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "registrar divisão de origem e destino nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t34_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t34_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19305,'$t34_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3432,19305,'','".AddSlashes(pg_result($resaco,$iresaco,'t34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3432,19306,'','".AddSlashes(pg_result($resaco,$iresaco,'t34_transferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3432,19307,'','".AddSlashes(pg_result($resaco,$iresaco,'t34_bem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3432,19308,'','".AddSlashes(pg_result($resaco,$iresaco,'t34_divisaoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3432,19309,'','".AddSlashes(pg_result($resaco,$iresaco,'t34_divisaodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3432,19399,'','".AddSlashes(pg_result($resaco,$iresaco,'t34_departamentodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3432,19398,'','".AddSlashes(pg_result($resaco,$iresaco,'t34_departamentoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benstransforigemdestino
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t34_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t34_sequencial = $t34_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "registrar divisão de origem e destino nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "registrar divisão de origem e destino nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t34_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:benstransforigemdestino";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     
     $sql .= " from benstransforigemdestino "; 
     $sql .= "   inner join db_depart origem   on origem.coddepto        = benstransforigemdestino.t34_departamentoorigem    ";
     $sql .= "   inner join db_depart destino  on destino.coddepto       = benstransforigemdestino.t34_departamentodestino   ";
     $sql .= "   inner join bens        on       bens.t52_bem              = benstransforigemdestino.t34_bem                   ";
     $sql .= "   inner join benstransf  on       benstransf.t93_codtran    = benstransforigemdestino.t34_transferencia         ";
     $sql .= "   left  join departdiv   on       departdiv.t30_codigo      = benstransforigemdestino.t34_divisaoorigem         ";
     $sql .= "                         and       departdiv.t30_codigo      = benstransforigemdestino.t34_divisaodestino        ";
     //$sql .= "   inner join db_config   on       db_config.codigo          = db_depart.instit                                  ";
     $sql .= "   inner join cgm         on       cgm.z01_numcgm            = bens.t52_numcgm                                   ";
     $sql .= "   inner join db_config   as a on  a.codigo                  = bens.t52_instit                                   ";
     //$sql .= "   inner join db_depart   on       db_depart.coddepto        = bens.t52_depart                                 ";
     $sql .= "   inner join clabens     on       clabens.t64_codcla        = bens.t52_codcla                                   ";
     $sql .= "   inner join bensmarca   on       bensmarca.t65_sequencial  = bens.t52_bensmarca                                ";
     $sql .= "   inner join bensmodelo  on       bensmodelo.t66_sequencial = bens.t52_bensmodelo                               ";
     $sql .= "   inner join bensmedida  on       bensmedida.t67_sequencial = bens.t52_bensmedida                               ";
     $sql .= "   inner join db_config   as b on  b.codigo                  = benstransf.t93_instit                             ";
     $sql .= "   inner join db_usuarios on       db_usuarios.id_usuario    = benstransf.t93_id_usuario                         ";
     //$sql .= "   inner join db_depart   on       db_depart.coddepto        = benstransf.t93_depart                           ";
     $sql .= "   inner join cgm  as c   on       c.z01_numcgm              = departdiv.t30_numcgm                              ";
     //$sql .= "   inner join db_depart   on       db_depart.coddepto        = departdiv.t30_depto";
     
     $sql2 = "";
     if($dbwhere==""){
       if($t34_sequencial!=null ){
         $sql2 .= " where benstransforigemdestino.t34_sequencial = $t34_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2    = " where $dbwhere";
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
   function sql_query_file ( $t34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benstransforigemdestino ";
     $sql2 = "";
     if($dbwhere==""){
       if($t34_sequencial!=null ){
         $sql2 .= " where benstransforigemdestino.t34_sequencial = $t34_sequencial "; 
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
  
  
  function sql_query_desprocessamento ( $t34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= "     from benstransforigemdestino ";
    $sql .= "left join inventariobem on benstransforigemdestino.t34_bem = inventariobem.t77_bens ";
    $sql2 = "";
    if($dbwhere==""){
      if($t34_sequencial!=null ){
        $sql2 .= " where benstransforigemdestino.t34_sequencial = $t34_sequencial ";
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