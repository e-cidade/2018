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

//MODULO: veiculos
//CLASSE DA ENTIDADE veicmanutitem
class cl_veicmanutitem { 
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
   var $ve63_codigo = 0; 
   var $ve63_veicmanut = 0; 
   var $ve63_descr = null; 
   var $ve63_quant = 0; 
   var $ve63_vlruni = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve63_codigo = int4 = C�digo Seq. 
                 ve63_veicmanut = int4 = Manuten��o 
                 ve63_descr = varchar(40) = Descri��o da Pe�a 
                 ve63_quant = int4 = Quantidade 
                 ve63_vlruni = float8 = Valor Unit�rio 
                 ";
   //funcao construtor da classe 
   function cl_veicmanutitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicmanutitem"); 
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
       $this->ve63_codigo = ($this->ve63_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_codigo"]:$this->ve63_codigo);
       $this->ve63_veicmanut = ($this->ve63_veicmanut == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_veicmanut"]:$this->ve63_veicmanut);
       $this->ve63_descr = ($this->ve63_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_descr"]:$this->ve63_descr);
       $this->ve63_quant = ($this->ve63_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_quant"]:$this->ve63_quant);
       $this->ve63_vlruni = ($this->ve63_vlruni == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_vlruni"]:$this->ve63_vlruni);
     }else{
       $this->ve63_codigo = ($this->ve63_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_codigo"]:$this->ve63_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve63_codigo){ 
      $this->atualizacampos();
     if($this->ve63_veicmanut == null ){ 
       $this->erro_sql = " Campo Manuten��o nao Informado.";
       $this->erro_campo = "ve63_veicmanut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve63_descr == null ){ 
       $this->erro_sql = " Campo Descri��o da Pe�a nao Informado.";
       $this->erro_campo = "ve63_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve63_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "ve63_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve63_vlruni == null ){ 
       $this->erro_sql = " Campo Valor Unit�rio nao Informado.";
       $this->erro_campo = "ve63_vlruni";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve63_codigo == "" || $ve63_codigo == null ){
       $result = db_query("select nextval('veicmanutitem_ve63_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicmanutitem_ve63_codigo_seq do campo: ve63_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve63_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicmanutitem_ve63_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve63_codigo)){
         $this->erro_sql = " Campo ve63_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve63_codigo = $ve63_codigo; 
       }
     }
     if(($this->ve63_codigo == null) || ($this->ve63_codigo == "") ){ 
       $this->erro_sql = " Campo ve63_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicmanutitem(
                                       ve63_codigo 
                                      ,ve63_veicmanut 
                                      ,ve63_descr 
                                      ,ve63_quant 
                                      ,ve63_vlruni 
                       )
                values (
                                $this->ve63_codigo 
                               ,$this->ve63_veicmanut 
                               ,'$this->ve63_descr' 
                               ,$this->ve63_quant 
                               ,$this->ve63_vlruni 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens da manuten��o dos ve�culos ($this->ve63_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens da manuten��o dos ve�culos j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens da manuten��o dos ve�culos ($this->ve63_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve63_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve63_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9338,'$this->ve63_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1604,9338,'','".AddSlashes(pg_result($resaco,0,'ve63_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1604,9339,'','".AddSlashes(pg_result($resaco,0,'ve63_veicmanut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1604,9340,'','".AddSlashes(pg_result($resaco,0,'ve63_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1604,9341,'','".AddSlashes(pg_result($resaco,0,'ve63_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1604,9342,'','".AddSlashes(pg_result($resaco,0,'ve63_vlruni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve63_codigo=null) { 
      $this->atualizacampos();
     $sql = " update veicmanutitem set ";
     $virgula = "";
     if(trim($this->ve63_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_codigo"])){ 
       $sql  .= $virgula." ve63_codigo = $this->ve63_codigo ";
       $virgula = ",";
       if(trim($this->ve63_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo Seq. nao Informado.";
         $this->erro_campo = "ve63_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve63_veicmanut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_veicmanut"])){ 
       $sql  .= $virgula." ve63_veicmanut = $this->ve63_veicmanut ";
       $virgula = ",";
       if(trim($this->ve63_veicmanut) == null ){ 
         $this->erro_sql = " Campo Manuten��o nao Informado.";
         $this->erro_campo = "ve63_veicmanut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve63_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_descr"])){ 
       $sql  .= $virgula." ve63_descr = '$this->ve63_descr' ";
       $virgula = ",";
       if(trim($this->ve63_descr) == null ){ 
         $this->erro_sql = " Campo Descri��o da Pe�a nao Informado.";
         $this->erro_campo = "ve63_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve63_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_quant"])){ 
       $sql  .= $virgula." ve63_quant = $this->ve63_quant ";
       $virgula = ",";
       if(trim($this->ve63_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "ve63_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve63_vlruni)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_vlruni"])){ 
       $sql  .= $virgula." ve63_vlruni = $this->ve63_vlruni ";
       $virgula = ",";
       if(trim($this->ve63_vlruni) == null ){ 
         $this->erro_sql = " Campo Valor Unit�rio nao Informado.";
         $this->erro_campo = "ve63_vlruni";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve63_codigo!=null){
       $sql .= " ve63_codigo = $this->ve63_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve63_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9338,'$this->ve63_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve63_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1604,9338,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_codigo'))."','$this->ve63_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve63_veicmanut"]))
           $resac = db_query("insert into db_acount values($acount,1604,9339,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_veicmanut'))."','$this->ve63_veicmanut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve63_descr"]))
           $resac = db_query("insert into db_acount values($acount,1604,9340,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_descr'))."','$this->ve63_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve63_quant"]))
           $resac = db_query("insert into db_acount values($acount,1604,9341,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_quant'))."','$this->ve63_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve63_vlruni"]))
           $resac = db_query("insert into db_acount values($acount,1604,9342,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_vlruni'))."','$this->ve63_vlruni',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da manuten��o dos ve�culos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve63_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da manuten��o dos ve�culos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve63_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve63_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve63_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve63_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9338,'$ve63_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1604,9338,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,9339,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_veicmanut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,9340,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,9341,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,9342,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_vlruni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicmanutitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve63_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve63_codigo = $ve63_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da manuten��o dos ve�culos nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve63_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da manuten��o dos ve�culos nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve63_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve63_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:veicmanutitem";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve63_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmanutitem ";
     $sql .= "      inner join veicmanut  on  veicmanut.ve62_codigo = veicmanutitem.ve63_veicmanut";
     $sql .= "      inner join veiccadtiposervico  on  veiccadtiposervico.ve28_codigo = veicmanut.ve62_veiccadtiposervico";
     $sql2 = "";
     if($dbwhere==""){
       if($ve63_codigo!=null ){
         $sql2 .= " where veicmanutitem.ve63_codigo = $ve63_codigo "; 
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
   function sql_query_file ( $ve63_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmanutitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve63_codigo!=null ){
         $sql2 .= " where veicmanutitem.ve63_codigo = $ve63_codigo "; 
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
   function sql_query_pcmater ( $ve63_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicmanutitem ";
     $sql .= "      inner join veicmanut  on  veicmanut.ve62_codigo = veicmanutitem.ve63_veicmanut";
     $sql .= "      inner join veiccadtiposervico  on  veiccadtiposervico.ve28_codigo = veicmanut.ve62_veiccadtiposervico";
     $sql .= "      left join veicmanutitempcmater on ve64_veicmanutitem = ve63_codigo";
     $sql .= "      left join pcmater on ve64_pcmater = pc01_codmater";
     $sql2 = "";
     if($dbwhere==""){
       if($ve63_codigo!=null ){
         $sql2 .= " where veicmanutitem.ve63_codigo = $ve63_codigo "; 
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
  
 function sql_query_info ( $ve62_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
   }
  
  /**
   * Retorna os Itens da Manuten��o
   *
   * @param integer $ve62_codigo
   * @param String  $campos
   * @param String  $ordem
   * @param String  $dbwhere
   * @return String
   */
  function sql_query_ItensManutencao ( $ve62_codigo=null, $campos="*", $ordem=null, $dbwhere=""){ 
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
     $sql .= " from veicmanut ";
     $sql .= " inner join veicmanutitem on ve63_veicmanut = ve62_codigo "; 
     $sql2 = "";
     if($dbwhere==""){
       if($ve62_codigo!=null ){
         $sql2 .= " where veicmanut.ve62_codigo = $ve62_codigo "; 
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