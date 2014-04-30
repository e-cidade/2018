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

//MODULO: Laboratório
//CLASSE DA ENTIDADE lab_examerequisito
class cl_lab_examerequisito { 
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
   var $la20_i_codigo = 0; 
   var $la20_i_requisito = 0; 
   var $la20_i_exame = 0; 
   var $la20_t_descr = null; 
   var $la20_d_inicio_dia = null; 
   var $la20_d_inicio_mes = null; 
   var $la20_d_inicio_ano = null; 
   var $la20_d_inicio = null; 
   var $la20_d_fim_dia = null; 
   var $la20_d_fim_mes = null; 
   var $la20_d_fim_ano = null; 
   var $la20_d_fim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la20_i_codigo = int4 = Código 
                 la20_i_requisito = int4 = Requisito 
                 la20_i_exame = int4 = Exame 
                 la20_t_descr = text = Descrição 
                 la20_d_inicio = date = Início 
                 la20_d_fim = date = Fim 
                 ";
   //funcao construtor da classe 
   function cl_lab_examerequisito() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_examerequisito"); 
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
       $this->la20_i_codigo = ($this->la20_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la20_i_codigo"]:$this->la20_i_codigo);
       $this->la20_i_requisito = ($this->la20_i_requisito == ""?@$GLOBALS["HTTP_POST_VARS"]["la20_i_requisito"]:$this->la20_i_requisito);
       $this->la20_i_exame = ($this->la20_i_exame == ""?@$GLOBALS["HTTP_POST_VARS"]["la20_i_exame"]:$this->la20_i_exame);
       $this->la20_t_descr = ($this->la20_t_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["la20_t_descr"]:$this->la20_t_descr);
       if($this->la20_d_inicio == ""){
         $this->la20_d_inicio_dia = ($this->la20_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la20_d_inicio_dia"]:$this->la20_d_inicio_dia);
         $this->la20_d_inicio_mes = ($this->la20_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la20_d_inicio_mes"]:$this->la20_d_inicio_mes);
         $this->la20_d_inicio_ano = ($this->la20_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la20_d_inicio_ano"]:$this->la20_d_inicio_ano);
         if($this->la20_d_inicio_dia != ""){
            $this->la20_d_inicio = $this->la20_d_inicio_ano."-".$this->la20_d_inicio_mes."-".$this->la20_d_inicio_dia;
         }
       }
       if($this->la20_d_fim == ""){
         $this->la20_d_fim_dia = ($this->la20_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la20_d_fim_dia"]:$this->la20_d_fim_dia);
         $this->la20_d_fim_mes = ($this->la20_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la20_d_fim_mes"]:$this->la20_d_fim_mes);
         $this->la20_d_fim_ano = ($this->la20_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la20_d_fim_ano"]:$this->la20_d_fim_ano);
         if($this->la20_d_fim_dia != ""){
            $this->la20_d_fim = $this->la20_d_fim_ano."-".$this->la20_d_fim_mes."-".$this->la20_d_fim_dia;
         }
       }
     }else{
       $this->la20_i_codigo = ($this->la20_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la20_i_codigo"]:$this->la20_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la20_i_codigo){ 
      $this->atualizacampos();
     if($this->la20_i_requisito == null ){ 
       $this->erro_sql = " Campo Requisito nao Informado.";
       $this->erro_campo = "la20_i_requisito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la20_i_exame == null ){ 
       $this->erro_sql = " Campo Exame nao Informado.";
       $this->erro_campo = "la20_i_exame";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la20_t_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "la20_t_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la20_d_inicio == null ){ 
       $this->la20_d_inicio = "null";
     }
     if($this->la20_d_fim == null ){ 
       $this->la20_d_fim = "null";
     }
     if($la20_i_codigo == "" || $la20_i_codigo == null ){
       $result = db_query("select nextval('lab_examerequisito_la20_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_examerequisito_la20_i_codigo_seq do campo: la20_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la20_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_examerequisito_la20_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la20_i_codigo)){
         $this->erro_sql = " Campo la20_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la20_i_codigo = $la20_i_codigo; 
       }
     }
     if(($this->la20_i_codigo == null) || ($this->la20_i_codigo == "") ){ 
       $this->erro_sql = " Campo la20_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_examerequisito(
                                       la20_i_codigo 
                                      ,la20_i_requisito 
                                      ,la20_i_exame 
                                      ,la20_t_descr 
                                      ,la20_d_inicio 
                                      ,la20_d_fim 
                       )
                values (
                                $this->la20_i_codigo 
                               ,$this->la20_i_requisito 
                               ,$this->la20_i_exame 
                               ,'$this->la20_t_descr' 
                               ,".($this->la20_d_inicio == "null" || $this->la20_d_inicio == ""?"null":"'".$this->la20_d_inicio."'")." 
                               ,".($this->la20_d_fim == "null" || $this->la20_d_fim == ""?"null":"'".$this->la20_d_fim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_examerequisito ($this->la20_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_examerequisito já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_examerequisito ($this->la20_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la20_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la20_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15781,'$this->la20_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2770,15781,'','".AddSlashes(pg_result($resaco,0,'la20_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2770,15782,'','".AddSlashes(pg_result($resaco,0,'la20_i_requisito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2770,15783,'','".AddSlashes(pg_result($resaco,0,'la20_i_exame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2770,15784,'','".AddSlashes(pg_result($resaco,0,'la20_t_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2770,15785,'','".AddSlashes(pg_result($resaco,0,'la20_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2770,15786,'','".AddSlashes(pg_result($resaco,0,'la20_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la20_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_examerequisito set ";
     $virgula = "";
     if(trim($this->la20_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la20_i_codigo"])){ 
       $sql  .= $virgula." la20_i_codigo = $this->la20_i_codigo ";
       $virgula = ",";
       if(trim($this->la20_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "la20_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la20_i_requisito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la20_i_requisito"])){ 
       $sql  .= $virgula." la20_i_requisito = $this->la20_i_requisito ";
       $virgula = ",";
       if(trim($this->la20_i_requisito) == null ){ 
         $this->erro_sql = " Campo Requisito nao Informado.";
         $this->erro_campo = "la20_i_requisito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la20_i_exame)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la20_i_exame"])){ 
       $sql  .= $virgula." la20_i_exame = $this->la20_i_exame ";
       $virgula = ",";
       if(trim($this->la20_i_exame) == null ){ 
         $this->erro_sql = " Campo Exame nao Informado.";
         $this->erro_campo = "la20_i_exame";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la20_t_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la20_t_descr"])){ 
       $sql  .= $virgula." la20_t_descr = '$this->la20_t_descr' ";
       $virgula = ",";
       if(trim($this->la20_t_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "la20_t_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la20_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la20_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la20_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." la20_d_inicio = '$this->la20_d_inicio' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la20_d_inicio_dia"])){ 
         $sql  .= $virgula." la20_d_inicio = null ";
         $virgula = ",";
       }
     }
     if(trim($this->la20_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la20_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la20_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." la20_d_fim = '$this->la20_d_fim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la20_d_fim_dia"])){ 
         $sql  .= $virgula." la20_d_fim = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($la20_i_codigo!=null){
       $sql .= " la20_i_codigo = $this->la20_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la20_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15781,'$this->la20_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la20_i_codigo"]) || $this->la20_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2770,15781,'".AddSlashes(pg_result($resaco,$conresaco,'la20_i_codigo'))."','$this->la20_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la20_i_requisito"]) || $this->la20_i_requisito != "")
           $resac = db_query("insert into db_acount values($acount,2770,15782,'".AddSlashes(pg_result($resaco,$conresaco,'la20_i_requisito'))."','$this->la20_i_requisito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la20_i_exame"]) || $this->la20_i_exame != "")
           $resac = db_query("insert into db_acount values($acount,2770,15783,'".AddSlashes(pg_result($resaco,$conresaco,'la20_i_exame'))."','$this->la20_i_exame',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la20_t_descr"]) || $this->la20_t_descr != "")
           $resac = db_query("insert into db_acount values($acount,2770,15784,'".AddSlashes(pg_result($resaco,$conresaco,'la20_t_descr'))."','$this->la20_t_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la20_d_inicio"]) || $this->la20_d_inicio != "")
           $resac = db_query("insert into db_acount values($acount,2770,15785,'".AddSlashes(pg_result($resaco,$conresaco,'la20_d_inicio'))."','$this->la20_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la20_d_fim"]) || $this->la20_d_fim != "")
           $resac = db_query("insert into db_acount values($acount,2770,15786,'".AddSlashes(pg_result($resaco,$conresaco,'la20_d_fim'))."','$this->la20_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_examerequisito nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la20_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_examerequisito nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la20_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la20_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15781,'$la20_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2770,15781,'','".AddSlashes(pg_result($resaco,$iresaco,'la20_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2770,15782,'','".AddSlashes(pg_result($resaco,$iresaco,'la20_i_requisito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2770,15783,'','".AddSlashes(pg_result($resaco,$iresaco,'la20_i_exame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2770,15784,'','".AddSlashes(pg_result($resaco,$iresaco,'la20_t_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2770,15785,'','".AddSlashes(pg_result($resaco,$iresaco,'la20_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2770,15786,'','".AddSlashes(pg_result($resaco,$iresaco,'la20_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_examerequisito
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la20_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la20_i_codigo = $la20_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_examerequisito nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la20_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_examerequisito nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la20_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la20_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_examerequisito";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_examerequisito ";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_examerequisito.la20_i_exame";
     $sql .= "      inner join lab_requisito  on  lab_requisito.la12_i_codigo = lab_examerequisito.la20_i_requisito";
     $sql2 = "";
     if($dbwhere==""){
       if($la20_i_codigo!=null ){
         $sql2 .= " where lab_examerequisito.la20_i_codigo = $la20_i_codigo "; 
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
   function sql_query_file ( $la20_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_examerequisito ";
     $sql2 = "";
     if($dbwhere==""){
       if($la20_i_codigo!=null ){
         $sql2 .= " where lab_examerequisito.la20_i_codigo = $la20_i_codigo "; 
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