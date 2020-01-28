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

//MODULO: educa��o
//CLASSE DA ENTIDADE relacaotrabalho
class cl_relacaotrabalho { 
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
   var $ed23_i_codigo = 0; 
   var $ed23_i_rechumanoescola = 0; 
   var $ed23_i_numero = 0; 
   var $ed23_i_regimetrabalho = 0; 
   var $ed23_i_areatrabalho = 0; 
   var $ed23_i_disciplina = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed23_i_codigo = int8 = C�digo 
                 ed23_i_rechumanoescola = int8 = Matr�cula 
                 ed23_i_numero = int4 = N�mero 
                 ed23_i_regimetrabalho = int8 = Regime de Trabalho 
                 ed23_i_areatrabalho = int8 = �rea de Trabalho 
                 ed23_i_disciplina = int8 = Disciplina 
                 ";
   //funcao construtor da classe 
   function cl_relacaotrabalho() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("relacaotrabalho"); 
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
       $this->ed23_i_codigo = ($this->ed23_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_i_codigo"]:$this->ed23_i_codigo);
       $this->ed23_i_rechumanoescola = ($this->ed23_i_rechumanoescola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_i_rechumanoescola"]:$this->ed23_i_rechumanoescola);
       $this->ed23_i_numero = ($this->ed23_i_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_i_numero"]:$this->ed23_i_numero);
       $this->ed23_i_regimetrabalho = ($this->ed23_i_regimetrabalho == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_i_regimetrabalho"]:$this->ed23_i_regimetrabalho);
       $this->ed23_i_areatrabalho = ($this->ed23_i_areatrabalho == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_i_areatrabalho"]:$this->ed23_i_areatrabalho);
       $this->ed23_i_disciplina = ($this->ed23_i_disciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_i_disciplina"]:$this->ed23_i_disciplina);
     }else{
       $this->ed23_i_codigo = ($this->ed23_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_i_codigo"]:$this->ed23_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed23_i_codigo){ 
      $this->atualizacampos();
     if($this->ed23_i_rechumanoescola == null ){ 
       $this->erro_sql = " Campo Matr�cula nao Informado.";
       $this->erro_campo = "ed23_i_rechumanoescola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed23_i_numero == null ){ 
       $this->ed23_i_numero = "null";
     }
     if($this->ed23_i_regimetrabalho == null ){ 
       $this->erro_sql = " Campo Regime de Trabalho nao Informado.";
       $this->erro_campo = "ed23_i_regimetrabalho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed23_i_areatrabalho == null ){ 
       $this->ed23_i_areatrabalho = "null";
     }
     if($this->ed23_i_disciplina == null ){ 
       $this->ed23_i_disciplina = "null";
     }
     if($ed23_i_codigo == "" || $ed23_i_codigo == null ){
       $result = db_query("select nextval('relacaotrabalho_ed23_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: relacaotrabalho_ed23_i_codigo_seq do campo: ed23_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed23_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from relacaotrabalho_ed23_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed23_i_codigo)){
         $this->erro_sql = " Campo ed23_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed23_i_codigo = $ed23_i_codigo; 
       }
     }
     if(($this->ed23_i_codigo == null) || ($this->ed23_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed23_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into relacaotrabalho(
                                       ed23_i_codigo 
                                      ,ed23_i_rechumanoescola 
                                      ,ed23_i_numero 
                                      ,ed23_i_regimetrabalho 
                                      ,ed23_i_areatrabalho 
                                      ,ed23_i_disciplina 
                       )
                values (
                                $this->ed23_i_codigo 
                               ,$this->ed23_i_rechumanoescola 
                               ,$this->ed23_i_numero 
                               ,$this->ed23_i_regimetrabalho 
                               ,$this->ed23_i_areatrabalho 
                               ,$this->ed23_i_disciplina 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Rela��es de trabalho ($this->ed23_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Rela��es de trabalho j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Rela��es de trabalho ($this->ed23_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed23_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed23_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008549,'$this->ed23_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010097,1008549,'','".AddSlashes(pg_result($resaco,0,'ed23_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010097,1008550,'','".AddSlashes(pg_result($resaco,0,'ed23_i_rechumanoescola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010097,1008554,'','".AddSlashes(pg_result($resaco,0,'ed23_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010097,1008552,'','".AddSlashes(pg_result($resaco,0,'ed23_i_regimetrabalho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010097,1008551,'','".AddSlashes(pg_result($resaco,0,'ed23_i_areatrabalho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010097,1008553,'','".AddSlashes(pg_result($resaco,0,'ed23_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed23_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update relacaotrabalho set ";
     $virgula = "";
     if(trim($this->ed23_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_codigo"])){ 
       $sql  .= $virgula." ed23_i_codigo = $this->ed23_i_codigo ";
       $virgula = ",";
       if(trim($this->ed23_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed23_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed23_i_rechumanoescola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_rechumanoescola"])){ 
       $sql  .= $virgula." ed23_i_rechumanoescola = $this->ed23_i_rechumanoescola ";
       $virgula = ",";
       if(trim($this->ed23_i_rechumanoescola) == null ){ 
         $this->erro_sql = " Campo Matr�cula nao Informado.";
         $this->erro_campo = "ed23_i_rechumanoescola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed23_i_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_numero"])){ 
       if(trim($this->ed23_i_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_numero"])){
          $this->ed23_i_numero = "null" ;
       }
       $sql  .= $virgula." ed23_i_numero = $this->ed23_i_numero ";
       $virgula = ",";
     }
     if(trim($this->ed23_i_regimetrabalho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_regimetrabalho"])){ 
       $sql  .= $virgula." ed23_i_regimetrabalho = $this->ed23_i_regimetrabalho ";
       $virgula = ",";
       if(trim($this->ed23_i_regimetrabalho) == null ){ 
         $this->erro_sql = " Campo Regime de Trabalho nao Informado.";
         $this->erro_campo = "ed23_i_regimetrabalho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed23_i_areatrabalho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_areatrabalho"])){ 
        if(trim($this->ed23_i_areatrabalho)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_areatrabalho"])){ 
           $this->ed23_i_areatrabalho = "null" ;
        } 
       $sql  .= $virgula." ed23_i_areatrabalho = $this->ed23_i_areatrabalho ";
       $virgula = ",";
     }
     if(trim($this->ed23_i_disciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_disciplina"])){ 
        if(trim($this->ed23_i_disciplina)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_disciplina"])){ 
           $this->ed23_i_disciplina = "null" ;
        } 
       $sql  .= $virgula." ed23_i_disciplina = $this->ed23_i_disciplina ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed23_i_codigo!=null){
       $sql .= " ed23_i_codigo = $this->ed23_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed23_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008549,'$this->ed23_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010097,1008549,'".AddSlashes(pg_result($resaco,$conresaco,'ed23_i_codigo'))."','$this->ed23_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_rechumanoescola"]))
           $resac = db_query("insert into db_acount values($acount,1010097,1008550,'".AddSlashes(pg_result($resaco,$conresaco,'ed23_i_rechumanoescola'))."','$this->ed23_i_rechumanoescola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_numero"]))
           $resac = db_query("insert into db_acount values($acount,1010097,1008554,'".AddSlashes(pg_result($resaco,$conresaco,'ed23_i_numero'))."','$this->ed23_i_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_regimetrabalho"]))
           $resac = db_query("insert into db_acount values($acount,1010097,1008552,'".AddSlashes(pg_result($resaco,$conresaco,'ed23_i_regimetrabalho'))."','$this->ed23_i_regimetrabalho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_areatrabalho"]))
           $resac = db_query("insert into db_acount values($acount,1010097,1008551,'".AddSlashes(pg_result($resaco,$conresaco,'ed23_i_areatrabalho'))."','$this->ed23_i_areatrabalho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_disciplina"]))
           $resac = db_query("insert into db_acount values($acount,1010097,1008553,'".AddSlashes(pg_result($resaco,$conresaco,'ed23_i_disciplina'))."','$this->ed23_i_disciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rela��es de trabalho nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed23_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Rela��es de trabalho nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed23_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed23_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed23_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed23_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008549,'$ed23_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010097,1008549,'','".AddSlashes(pg_result($resaco,$iresaco,'ed23_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010097,1008550,'','".AddSlashes(pg_result($resaco,$iresaco,'ed23_i_rechumanoescola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010097,1008554,'','".AddSlashes(pg_result($resaco,$iresaco,'ed23_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010097,1008552,'','".AddSlashes(pg_result($resaco,$iresaco,'ed23_i_regimetrabalho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010097,1008551,'','".AddSlashes(pg_result($resaco,$iresaco,'ed23_i_areatrabalho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010097,1008553,'','".AddSlashes(pg_result($resaco,$iresaco,'ed23_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from relacaotrabalho
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed23_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed23_i_codigo = $ed23_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rela��es de trabalho nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed23_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Rela��es de trabalho nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed23_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed23_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:relacaotrabalho";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from relacaotrabalho ";
     $sql .= "      left join disciplina  on  disciplina.ed12_i_codigo = relacaotrabalho.ed23_i_disciplina";
     $sql .= "      left join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      left join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql .= "      left join areatrabalho  on  areatrabalho.ed25_i_codigo = relacaotrabalho.ed23_i_areatrabalho";
     $sql .= "      left join regimetrabalho  on  regimetrabalho.ed24_i_codigo = relacaotrabalho.ed23_i_regimetrabalho";
     $sql .= "      inner join rechumanoescola  on  rechumanoescola.ed75_i_codigo = relacaotrabalho.ed23_i_rechumanoescola";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = rechumanoescola.ed75_i_escola";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = rechumanoescola.ed75_i_rechumano";
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($ed23_i_codigo!=null ){
         $sql2 .= " where relacaotrabalho.ed23_i_codigo = $ed23_i_codigo "; 
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
   function sql_query_file ( $ed23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from relacaotrabalho ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed23_i_codigo!=null ){
         $sql2 .= " where relacaotrabalho.ed23_i_codigo = $ed23_i_codigo "; 
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