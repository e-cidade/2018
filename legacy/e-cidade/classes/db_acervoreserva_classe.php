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

//MODULO: biblioteca
//CLASSE DA ENTIDADE acervoreserva
class cl_acervoreserva { 
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
   var $bi20_codigo = 0; 
   var $bi20_reserva = 0; 
   var $bi20_acervo = 0; 
   var $bi20_data_dia = null; 
   var $bi20_data_mes = null; 
   var $bi20_data_ano = null; 
   var $bi20_data = null; 
   var $bi20_hora = null; 
   var $bi20_retirada_dia = null; 
   var $bi20_retirada_mes = null; 
   var $bi20_retirada_ano = null; 
   var $bi20_retirada = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 bi20_codigo = int4 = Código 
                 bi20_reserva = int4 = Código da Reserva 
                 bi20_acervo = int4 = Código do Acervo 
                 bi20_data = date = Reservar para 
                 bi20_hora = char(5) = Hora da Reserva 
                 bi20_retirada = date = Data da Retirada 
                 ";
   //funcao construtor da classe 
   function cl_acervoreserva() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acervoreserva"); 
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
       $this->bi20_codigo = ($this->bi20_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi20_codigo"]:$this->bi20_codigo);
       $this->bi20_reserva = ($this->bi20_reserva == ""?@$GLOBALS["HTTP_POST_VARS"]["bi20_reserva"]:$this->bi20_reserva);
       $this->bi20_acervo = ($this->bi20_acervo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi20_acervo"]:$this->bi20_acervo);
       if($this->bi20_data == ""){
         $this->bi20_data_dia = ($this->bi20_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi20_data_dia"]:$this->bi20_data_dia);
         $this->bi20_data_mes = ($this->bi20_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi20_data_mes"]:$this->bi20_data_mes);
         $this->bi20_data_ano = ($this->bi20_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi20_data_ano"]:$this->bi20_data_ano);
         if($this->bi20_data_dia != ""){
            $this->bi20_data = $this->bi20_data_ano."-".$this->bi20_data_mes."-".$this->bi20_data_dia;
         }
       }
       $this->bi20_hora = ($this->bi20_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["bi20_hora"]:$this->bi20_hora);
       if($this->bi20_retirada == ""){
         $this->bi20_retirada_dia = ($this->bi20_retirada_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi20_retirada_dia"]:$this->bi20_retirada_dia);
         $this->bi20_retirada_mes = ($this->bi20_retirada_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi20_retirada_mes"]:$this->bi20_retirada_mes);
         $this->bi20_retirada_ano = ($this->bi20_retirada_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi20_retirada_ano"]:$this->bi20_retirada_ano);
         if($this->bi20_retirada_dia != ""){
            $this->bi20_retirada = $this->bi20_retirada_ano."-".$this->bi20_retirada_mes."-".$this->bi20_retirada_dia;
         }
       }
     }else{
       $this->bi20_codigo = ($this->bi20_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi20_codigo"]:$this->bi20_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($bi20_codigo){ 
      $this->atualizacampos();
     if($this->bi20_reserva == null ){ 
       $this->erro_sql = " Campo Código da Reserva nao Informado.";
       $this->erro_campo = "bi20_reserva";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi20_acervo == null ){ 
       $this->erro_sql = " Campo Código do Acervo nao Informado.";
       $this->erro_campo = "bi20_acervo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi20_data == null ){ 
       $this->erro_sql = " Campo Reservar para nao Informado.";
       $this->erro_campo = "bi20_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi20_hora == null ){ 
       $this->erro_sql = " Campo Hora da Reserva nao Informado.";
       $this->erro_campo = "bi20_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi20_retirada == null ){ 
       $this->bi20_retirada = "null";
     }
     if($bi20_codigo == "" || $bi20_codigo == null ){
       $result = @pg_query("select nextval('acervoreserva_bi20_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acervoreserva_bi20_codigo_seq do campo: bi20_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->bi20_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from acervoreserva_bi20_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi20_codigo)){
         $this->erro_sql = " Campo bi20_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi20_codigo = $bi20_codigo; 
       }
     }
     if(($this->bi20_codigo == null) || ($this->bi20_codigo == "") ){ 
       $this->erro_sql = " Campo bi20_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acervoreserva(
                                       bi20_codigo 
                                      ,bi20_reserva 
                                      ,bi20_acervo 
                                      ,bi20_data 
                                      ,bi20_hora 
                                      ,bi20_retirada 
                       )
                values (
                                $this->bi20_codigo 
                               ,$this->bi20_reserva 
                               ,$this->bi20_acervo 
                               ,".($this->bi20_data == "null" || $this->bi20_data == ""?"null":"'".$this->bi20_data."'")." 
                               ,'$this->bi20_hora' 
                               ,".($this->bi20_retirada == "null" || $this->bi20_retirada == ""?"null":"'".$this->bi20_retirada."'")." 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acervos da Reserva ($this->bi20_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acervos da Reserva já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acervos da Reserva ($this->bi20_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi20_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bi20_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1008157,'$this->bi20_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1008025,1008157,'','".AddSlashes(pg_result($resaco,0,'bi20_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1008025,1008927,'','".AddSlashes(pg_result($resaco,0,'bi20_reserva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1008025,1008928,'','".AddSlashes(pg_result($resaco,0,'bi20_acervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1008025,1008946,'','".AddSlashes(pg_result($resaco,0,'bi20_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1008025,1008947,'','".AddSlashes(pg_result($resaco,0,'bi20_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1008025,1008948,'','".AddSlashes(pg_result($resaco,0,'bi20_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($bi20_codigo=null) { 
      $this->atualizacampos();
     $sql = " update acervoreserva set ";
     $virgula = "";
     if(trim($this->bi20_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi20_codigo"])){ 
       $sql  .= $virgula." bi20_codigo = $this->bi20_codigo ";
       $virgula = ",";
       if(trim($this->bi20_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "bi20_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi20_reserva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi20_reserva"])){ 
       $sql  .= $virgula." bi20_reserva = $this->bi20_reserva ";
       $virgula = ",";
       if(trim($this->bi20_reserva) == null ){ 
         $this->erro_sql = " Campo Código da Reserva nao Informado.";
         $this->erro_campo = "bi20_reserva";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi20_acervo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi20_acervo"])){ 
       $sql  .= $virgula." bi20_acervo = $this->bi20_acervo ";
       $virgula = ",";
       if(trim($this->bi20_acervo) == null ){ 
         $this->erro_sql = " Campo Código do Acervo nao Informado.";
         $this->erro_campo = "bi20_acervo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi20_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi20_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi20_data_dia"] !="") ){ 
       $sql  .= $virgula." bi20_data = '$this->bi20_data' ";
       $virgula = ",";
       if(trim($this->bi20_data) == null ){ 
         $this->erro_sql = " Campo Reservar para nao Informado.";
         $this->erro_campo = "bi20_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi20_data_dia"])){ 
         $sql  .= $virgula." bi20_data = null ";
         $virgula = ",";
         if(trim($this->bi20_data) == null ){ 
           $this->erro_sql = " Campo Reservar para nao Informado.";
           $this->erro_campo = "bi20_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bi20_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi20_hora"])){ 
       $sql  .= $virgula." bi20_hora = '$this->bi20_hora' ";
       $virgula = ",";
       if(trim($this->bi20_hora) == null ){ 
         $this->erro_sql = " Campo Hora da Reserva nao Informado.";
         $this->erro_campo = "bi20_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi20_retirada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi20_retirada_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi20_retirada_dia"] !="") ){ 
       $sql  .= $virgula." bi20_retirada = '$this->bi20_retirada' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi20_retirada_dia"])){ 
         $sql  .= $virgula." bi20_retirada = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($bi20_codigo!=null){
       $sql .= " bi20_codigo = $this->bi20_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bi20_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1008157,'$this->bi20_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi20_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1008025,1008157,'".AddSlashes(pg_result($resaco,$conresaco,'bi20_codigo'))."','$this->bi20_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi20_reserva"]))
           $resac = pg_query("insert into db_acount values($acount,1008025,1008927,'".AddSlashes(pg_result($resaco,$conresaco,'bi20_reserva'))."','$this->bi20_reserva',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi20_acervo"]))
           $resac = pg_query("insert into db_acount values($acount,1008025,1008928,'".AddSlashes(pg_result($resaco,$conresaco,'bi20_acervo'))."','$this->bi20_acervo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi20_data"]))
           $resac = pg_query("insert into db_acount values($acount,1008025,1008946,'".AddSlashes(pg_result($resaco,$conresaco,'bi20_data'))."','$this->bi20_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi20_hora"]))
           $resac = pg_query("insert into db_acount values($acount,1008025,1008947,'".AddSlashes(pg_result($resaco,$conresaco,'bi20_hora'))."','$this->bi20_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi20_retirada"]))
           $resac = pg_query("insert into db_acount values($acount,1008025,1008948,'".AddSlashes(pg_result($resaco,$conresaco,'bi20_retirada'))."','$this->bi20_retirada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acervos da Reserva nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi20_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acervos da Reserva nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi20_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi20_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($bi20_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bi20_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1008157,'$bi20_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1008025,1008157,'','".AddSlashes(pg_result($resaco,$iresaco,'bi20_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1008025,1008927,'','".AddSlashes(pg_result($resaco,$iresaco,'bi20_reserva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1008025,1008928,'','".AddSlashes(pg_result($resaco,$iresaco,'bi20_acervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1008025,1008946,'','".AddSlashes(pg_result($resaco,$iresaco,'bi20_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1008025,1008947,'','".AddSlashes(pg_result($resaco,$iresaco,'bi20_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1008025,1008948,'','".AddSlashes(pg_result($resaco,$iresaco,'bi20_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acervoreserva
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bi20_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bi20_codigo = $bi20_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acervos da Reserva nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi20_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acervos da Reserva nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi20_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi20_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:acervoreserva";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $bi20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acervoreserva ";
     $sql .= "      inner join acervo  on  acervo.bi06_seq = acervoreserva.bi20_acervo";
     $sql .= "      inner join reserva  on  reserva.bi14_codigo = acervoreserva.bi20_reserva";
     $sql .= "      inner join carteira  on  carteira.bi16_codigo = reserva.bi14_carteira";
     $sql .= "      inner join leitorcategoria on leitorcategoria.bi07_codigo = carteira.bi16_leitorcategoria";
     $sql .= "      inner join leitor on leitor.bi10_codigo = carteira.bi16_leitor";
     $sql .= "      left join leitoraluno on leitoraluno.bi11_leitor = leitor.bi10_codigo";
     $sql .= "      left join aluno on aluno.ed47_i_codigo = leitoraluno.bi11_aluno";
     $sql .= "      left join alunocurso on alunocurso.ed56_i_aluno = ed47_i_codigo";
     $sql .= "      left join leitorfunc on leitorfunc.bi12_leitor = leitor.bi10_codigo";
     $sql .= "      left join rhpessoal on rhpessoal.rh01_regist = leitorfunc.bi12_rechumano";
     $sql .= "      left join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join leitorpublico on leitorpublico.bi13_leitor = leitor.bi10_codigo";
     $sql .= "      left join cgm as cgmpub on cgmpub.z01_numcgm = leitorpublico.bi13_numcgm";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = acervo.bi06_biblioteca";
     $sql .= "      inner join editora  on  editora.bi02_codigo = acervo.bi06_editora";
     $sql .= "      inner join classiliteraria  on  classiliteraria.bi03_codigo = acervo.bi06_classiliteraria";
     $sql .= "      inner join aquisicao  on  aquisicao.bi04_codigo = acervo.bi06_aquisicao";
     $sql .= "      inner join tipoitem  on  tipoitem.bi05_codigo = acervo.bi06_tipoitem";

     $sql2 = "";
     if($dbwhere==""){
       if($bi20_codigo!=null ){
         $sql2 .= " where acervoreserva.bi20_codigo = $bi20_codigo "; 
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
   function sql_query_file ( $bi20_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acervoreserva ";
     $sql2 = "";
     if($dbwhere==""){
       if($bi20_codigo!=null ){
         $sql2 .= " where acervoreserva.bi20_codigo = $bi20_codigo "; 
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