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

//MODULO: cemiterio
//CLASSE DA ENTIDADE sepulturas
class cl_sepulturas { 
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
   var $cm05_i_codigo = 0; 
   var $cm05_i_sepultamento = 0; 
   var $cm05_c_quadra = null; 
   var $cm05_i_lote = 0; 
   var $cm05_c_campa = null; 
   var $cm05_d_entrada_dia = null; 
   var $cm05_d_entrada_mes = null; 
   var $cm05_d_entrada_ano = null; 
   var $cm05_d_entrada = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cm05_i_codigo = int8 = C�digo 
                 cm05_i_sepultamento = int8 = Sepultamento 
                 cm05_c_quadra = char(3) = Quadra 
                 cm05_i_lote = int4 = Lote 
                 cm05_c_campa = char(12) = Campa 
                 cm05_d_entrada = date = Entrada 
                 ";
   //funcao construtor da classe 
   function cl_sepulturas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sepulturas"); 
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
       $this->cm05_i_codigo = ($this->cm05_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm05_i_codigo"]:$this->cm05_i_codigo);
       $this->cm05_i_sepultamento = ($this->cm05_i_sepultamento == ""?@$GLOBALS["HTTP_POST_VARS"]["cm05_i_sepultamento"]:$this->cm05_i_sepultamento);
       $this->cm05_c_quadra = ($this->cm05_c_quadra == ""?@$GLOBALS["HTTP_POST_VARS"]["cm05_c_quadra"]:$this->cm05_c_quadra);
       $this->cm05_i_lote = ($this->cm05_i_lote == ""?@$GLOBALS["HTTP_POST_VARS"]["cm05_i_lote"]:$this->cm05_i_lote);
       $this->cm05_c_campa = ($this->cm05_c_campa == ""?@$GLOBALS["HTTP_POST_VARS"]["cm05_c_campa"]:$this->cm05_c_campa);
       if($this->cm05_d_entrada == ""){
         $this->cm05_d_entrada_dia = ($this->cm05_d_entrada_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm05_d_entrada_dia"]:$this->cm05_d_entrada_dia);
         $this->cm05_d_entrada_mes = ($this->cm05_d_entrada_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm05_d_entrada_mes"]:$this->cm05_d_entrada_mes);
         $this->cm05_d_entrada_ano = ($this->cm05_d_entrada_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm05_d_entrada_ano"]:$this->cm05_d_entrada_ano);
         if($this->cm05_d_entrada_dia != ""){
            $this->cm05_d_entrada = $this->cm05_d_entrada_ano."-".$this->cm05_d_entrada_mes."-".$this->cm05_d_entrada_dia;
         }
       }
     }else{
       $this->cm05_i_codigo = ($this->cm05_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm05_i_codigo"]:$this->cm05_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm05_i_codigo){ 
      $this->atualizacampos();
     if($this->cm05_i_sepultamento == null ){ 
       $this->erro_sql = " Campo Sepultamento nao Informado.";
       $this->erro_campo = "cm05_i_sepultamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm05_c_quadra == null ){ 
       $this->erro_sql = " Campo Quadra nao Informado.";
       $this->erro_campo = "cm05_c_quadra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm05_i_lote == null ){ 
       $this->erro_sql = " Campo Lote nao Informado.";
       $this->erro_campo = "cm05_i_lote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm05_c_campa == null ){ 
       $this->erro_sql = " Campo Campa nao Informado.";
       $this->erro_campo = "cm05_c_campa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm05_d_entrada == null ){ 
       $this->erro_sql = " Campo Entrada nao Informado.";
       $this->erro_campo = "cm05_d_entrada_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm05_i_codigo == "" || $cm05_i_codigo == null ){
       $result = @pg_query("select nextval('sepulturas_cm05_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sepulturas_cm05_i_codigo_seq do campo: cm05_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cm05_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from sepulturas_cm05_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm05_i_codigo)){
         $this->erro_sql = " Campo cm05_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm05_i_codigo = $cm05_i_codigo; 
       }
     }
     if(($this->cm05_i_codigo == null) || ($this->cm05_i_codigo == "") ){ 
       $this->erro_sql = " Campo cm05_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sepulturas(
                                       cm05_i_codigo 
                                      ,cm05_i_sepultamento 
                                      ,cm05_c_quadra 
                                      ,cm05_i_lote 
                                      ,cm05_c_campa 
                                      ,cm05_d_entrada 
                       )
                values (
                                $this->cm05_i_codigo 
                               ,$this->cm05_i_sepultamento 
                               ,'$this->cm05_c_quadra' 
                               ,$this->cm05_i_lote 
                               ,'$this->cm05_c_campa' 
                               ,".($this->cm05_d_entrada == "null" || $this->cm05_d_entrada == ""?"null":"'".$this->cm05_d_entrada."'")." 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sepulturas ($this->cm05_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sepulturas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sepulturas ($this->cm05_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm05_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm05_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1000072,'$this->cm05_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1000019,1000072,'','".AddSlashes(pg_result($resaco,0,'cm05_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1000019,1000073,'','".AddSlashes(pg_result($resaco,0,'cm05_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1000019,1000074,'','".AddSlashes(pg_result($resaco,0,'cm05_c_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1000019,1000075,'','".AddSlashes(pg_result($resaco,0,'cm05_i_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1000019,1000076,'','".AddSlashes(pg_result($resaco,0,'cm05_c_campa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1000019,1000137,'','".AddSlashes(pg_result($resaco,0,'cm05_d_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cm05_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sepulturas set ";
     $virgula = "";
     if(trim($this->cm05_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm05_i_codigo"])){ 
       $sql  .= $virgula." cm05_i_codigo = $this->cm05_i_codigo ";
       $virgula = ",";
       if(trim($this->cm05_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "cm05_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm05_i_sepultamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm05_i_sepultamento"])){ 
       $sql  .= $virgula." cm05_i_sepultamento = $this->cm05_i_sepultamento ";
       $virgula = ",";
       if(trim($this->cm05_i_sepultamento) == null ){ 
         $this->erro_sql = " Campo Sepultamento nao Informado.";
         $this->erro_campo = "cm05_i_sepultamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm05_c_quadra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm05_c_quadra"])){ 
       $sql  .= $virgula." cm05_c_quadra = '$this->cm05_c_quadra' ";
       $virgula = ",";
       if(trim($this->cm05_c_quadra) == null ){ 
         $this->erro_sql = " Campo Quadra nao Informado.";
         $this->erro_campo = "cm05_c_quadra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm05_i_lote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm05_i_lote"])){ 
       $sql  .= $virgula." cm05_i_lote = $this->cm05_i_lote ";
       $virgula = ",";
       if(trim($this->cm05_i_lote) == null ){ 
         $this->erro_sql = " Campo Lote nao Informado.";
         $this->erro_campo = "cm05_i_lote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm05_c_campa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm05_c_campa"])){ 
       $sql  .= $virgula." cm05_c_campa = '$this->cm05_c_campa' ";
       $virgula = ",";
       if(trim($this->cm05_c_campa) == null ){ 
         $this->erro_sql = " Campo Campa nao Informado.";
         $this->erro_campo = "cm05_c_campa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm05_d_entrada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm05_d_entrada_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm05_d_entrada_dia"] !="") ){ 
       $sql  .= $virgula." cm05_d_entrada = '$this->cm05_d_entrada' ";
       $virgula = ",";
       if(trim($this->cm05_d_entrada) == null ){ 
         $this->erro_sql = " Campo Entrada nao Informado.";
         $this->erro_campo = "cm05_d_entrada_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm05_d_entrada_dia"])){ 
         $sql  .= $virgula." cm05_d_entrada = null ";
         $virgula = ",";
         if(trim($this->cm05_d_entrada) == null ){ 
           $this->erro_sql = " Campo Entrada nao Informado.";
           $this->erro_campo = "cm05_d_entrada_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($cm05_i_codigo!=null){
       $sql .= " cm05_i_codigo = $this->cm05_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm05_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1000072,'$this->cm05_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm05_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1000019,1000072,'".AddSlashes(pg_result($resaco,$conresaco,'cm05_i_codigo'))."','$this->cm05_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm05_i_sepultamento"]))
           $resac = pg_query("insert into db_acount values($acount,1000019,1000073,'".AddSlashes(pg_result($resaco,$conresaco,'cm05_i_sepultamento'))."','$this->cm05_i_sepultamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm05_c_quadra"]))
           $resac = pg_query("insert into db_acount values($acount,1000019,1000074,'".AddSlashes(pg_result($resaco,$conresaco,'cm05_c_quadra'))."','$this->cm05_c_quadra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm05_i_lote"]))
           $resac = pg_query("insert into db_acount values($acount,1000019,1000075,'".AddSlashes(pg_result($resaco,$conresaco,'cm05_i_lote'))."','$this->cm05_i_lote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm05_c_campa"]))
           $resac = pg_query("insert into db_acount values($acount,1000019,1000076,'".AddSlashes(pg_result($resaco,$conresaco,'cm05_c_campa'))."','$this->cm05_c_campa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm05_d_entrada"]))
           $resac = pg_query("insert into db_acount values($acount,1000019,1000137,'".AddSlashes(pg_result($resaco,$conresaco,'cm05_d_entrada'))."','$this->cm05_d_entrada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sepulturas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm05_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sepulturas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm05_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm05_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cm05_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm05_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1000072,'$this->cm05_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1000019,1000072,'','".AddSlashes(pg_result($resaco,$iresaco,'cm05_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1000019,1000073,'','".AddSlashes(pg_result($resaco,$iresaco,'cm05_i_sepultamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1000019,1000074,'','".AddSlashes(pg_result($resaco,$iresaco,'cm05_c_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1000019,1000075,'','".AddSlashes(pg_result($resaco,$iresaco,'cm05_i_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1000019,1000076,'','".AddSlashes(pg_result($resaco,$iresaco,'cm05_c_campa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1000019,1000137,'','".AddSlashes(pg_result($resaco,$iresaco,'cm05_d_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sepulturas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm05_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm05_i_codigo = $cm05_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sepulturas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm05_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sepulturas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm05_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm05_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:sepulturas";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cm05_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sepulturas ";
     $sql .= "      inner join sepultamentos  on  sepultamentos.cm01_i_codigo = sepulturas.cm05_i_sepultamento";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = sepultamentos.cm01_i_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sepultamentos.cm01_i_funcionario";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = sepultamentos.cm01_i_medico";
     $sql .= "      inner join causa  on  causa.cm04_i_codigo = sepultamentos.cm01_i_causa";
     $sql .= "      inner join cemiterio  on  cemiterio.cm14_i_codigo = sepultamentos.cm01_i_cemiterio";
     $sql .= "      inner join hospitais  on  hospitais.cm18_i_hospital = sepultamentos.cm01_i_hospital";
     $sql .= "      inner join funerarias  on  funerarias.cm17_i_funeraria = sepultamentos.cm01_i_funeraria";
     $sql2 = "";
     if($dbwhere==""){
       if($cm05_i_codigo!=null ){
         $sql2 .= " where sepulturas.cm05_i_codigo = $cm05_i_codigo "; 
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
   function sql_query_file ( $cm05_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sepulturas ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm05_i_codigo!=null ){
         $sql2 .= " where sepulturas.cm05_i_codigo = $cm05_i_codigo "; 
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