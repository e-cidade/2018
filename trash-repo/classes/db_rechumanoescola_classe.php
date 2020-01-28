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

//MODULO: escola
//CLASSE DA ENTIDADE rechumanoescola
class cl_rechumanoescola { 
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
   var $ed75_i_codigo = 0; 
   var $ed75_i_escola = 0; 
   var $ed75_i_rechumano = 0; 
   var $ed75_d_ingresso_dia = null; 
   var $ed75_d_ingresso_mes = null; 
   var $ed75_d_ingresso_ano = null; 
   var $ed75_d_ingresso = null; 
   var $ed75_c_simultaneo = null; 
   var $ed75_i_saidaescola_dia = null; 
   var $ed75_i_saidaescola_mes = null; 
   var $ed75_i_saidaescola_ano = null; 
   var $ed75_i_saidaescola = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed75_i_codigo = int8 = C�digo 
                 ed75_i_escola = int8 = Escola 
                 ed75_i_rechumano = int8 = Matr�cula 
                 ed75_d_ingresso = date = Data de Ingresso 
                 ed75_c_simultaneo = char(1) = Atende Simultaneamente 
                 ed75_i_saidaescola = date = Data de Saida 
                 ";
   //funcao construtor da classe 
   function cl_rechumanoescola() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rechumanoescola"); 
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
       $this->ed75_i_codigo = ($this->ed75_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed75_i_codigo"]:$this->ed75_i_codigo);
       $this->ed75_i_escola = ($this->ed75_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed75_i_escola"]:$this->ed75_i_escola);
       $this->ed75_i_rechumano = ($this->ed75_i_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed75_i_rechumano"]:$this->ed75_i_rechumano);
       if($this->ed75_d_ingresso == ""){
         $this->ed75_d_ingresso_dia = ($this->ed75_d_ingresso_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed75_d_ingresso_dia"]:$this->ed75_d_ingresso_dia);
         $this->ed75_d_ingresso_mes = ($this->ed75_d_ingresso_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed75_d_ingresso_mes"]:$this->ed75_d_ingresso_mes);
         $this->ed75_d_ingresso_ano = ($this->ed75_d_ingresso_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed75_d_ingresso_ano"]:$this->ed75_d_ingresso_ano);
         if($this->ed75_d_ingresso_dia != ""){
            $this->ed75_d_ingresso = $this->ed75_d_ingresso_ano."-".$this->ed75_d_ingresso_mes."-".$this->ed75_d_ingresso_dia;
         }
       }
       $this->ed75_c_simultaneo = ($this->ed75_c_simultaneo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed75_c_simultaneo"]:$this->ed75_c_simultaneo);
       if($this->ed75_i_saidaescola == ""){
         $this->ed75_i_saidaescola_dia = ($this->ed75_i_saidaescola_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed75_i_saidaescola_dia"]:$this->ed75_i_saidaescola_dia);
         $this->ed75_i_saidaescola_mes = ($this->ed75_i_saidaescola_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed75_i_saidaescola_mes"]:$this->ed75_i_saidaescola_mes);
         $this->ed75_i_saidaescola_ano = ($this->ed75_i_saidaescola_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed75_i_saidaescola_ano"]:$this->ed75_i_saidaescola_ano);
         if($this->ed75_i_saidaescola_dia != ""){
            $this->ed75_i_saidaescola = $this->ed75_i_saidaescola_ano."-".$this->ed75_i_saidaescola_mes."-".$this->ed75_i_saidaescola_dia;
         }
       }
     }else{
       $this->ed75_i_codigo = ($this->ed75_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed75_i_codigo"]:$this->ed75_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed75_i_codigo){ 
      $this->atualizacampos();
     if($this->ed75_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed75_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed75_i_rechumano == null ){ 
       $this->erro_sql = " Campo Matr�cula nao Informado.";
       $this->erro_campo = "ed75_i_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed75_d_ingresso == null ){ 
       $this->erro_sql = " Campo Data de Ingresso nao Informado.";
       $this->erro_campo = "ed75_d_ingresso_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed75_c_simultaneo == null ){ 
       $this->erro_sql = " Campo Atende Simultaneamente nao Informado.";
       $this->erro_campo = "ed75_c_simultaneo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed75_i_saidaescola == null ){ 
       $this->ed75_i_saidaescola = "null";
     }
     if($ed75_i_codigo == "" || $ed75_i_codigo == null ){
       $result = db_query("select nextval('rechumanoescola_ed75_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rechumanoescola_ed75_i_codigo_seq do campo: ed75_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed75_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rechumanoescola_ed75_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed75_i_codigo)){
         $this->erro_sql = " Campo ed75_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed75_i_codigo = $ed75_i_codigo; 
       }
     }
     if(($this->ed75_i_codigo == null) || ($this->ed75_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed75_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rechumanoescola(
                                       ed75_i_codigo 
                                      ,ed75_i_escola 
                                      ,ed75_i_rechumano 
                                      ,ed75_d_ingresso 
                                      ,ed75_c_simultaneo 
                                      ,ed75_i_saidaescola 
                       )
                values (
                                $this->ed75_i_codigo 
                               ,$this->ed75_i_escola 
                               ,$this->ed75_i_rechumano 
                               ,".($this->ed75_d_ingresso == "null" || $this->ed75_d_ingresso == ""?"null":"'".$this->ed75_d_ingresso."'")." 
                               ,'$this->ed75_c_simultaneo' 
                               ,".($this->ed75_i_saidaescola == "null" || $this->ed75_i_saidaescola == ""?"null":"'".$this->ed75_i_saidaescola."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Liga��o do RH �s escolas ($this->ed75_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Liga��o do RH �s escolas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Liga��o do RH �s escolas ($this->ed75_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed75_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed75_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008536,'$this->ed75_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010094,1008536,'','".AddSlashes(pg_result($resaco,0,'ed75_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010094,1008537,'','".AddSlashes(pg_result($resaco,0,'ed75_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010094,1008538,'','".AddSlashes(pg_result($resaco,0,'ed75_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010094,1008564,'','".AddSlashes(pg_result($resaco,0,'ed75_d_ingresso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010094,17612,'','".AddSlashes(pg_result($resaco,0,'ed75_c_simultaneo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010094,19748,'','".AddSlashes(pg_result($resaco,0,'ed75_i_saidaescola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed75_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rechumanoescola set ";
     $virgula = "";
     if(trim($this->ed75_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed75_i_codigo"])){ 
       $sql  .= $virgula." ed75_i_codigo = $this->ed75_i_codigo ";
       $virgula = ",";
       if(trim($this->ed75_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed75_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed75_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed75_i_escola"])){ 
       $sql  .= $virgula." ed75_i_escola = $this->ed75_i_escola ";
       $virgula = ",";
       if(trim($this->ed75_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed75_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed75_i_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed75_i_rechumano"])){ 
       $sql  .= $virgula." ed75_i_rechumano = $this->ed75_i_rechumano ";
       $virgula = ",";
       if(trim($this->ed75_i_rechumano) == null ){ 
         $this->erro_sql = " Campo Matr�cula nao Informado.";
         $this->erro_campo = "ed75_i_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed75_d_ingresso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed75_d_ingresso_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed75_d_ingresso_dia"] !="") ){ 
       $sql  .= $virgula." ed75_d_ingresso = '$this->ed75_d_ingresso' ";
       $virgula = ",";
       if(trim($this->ed75_d_ingresso) == null ){ 
         $this->erro_sql = " Campo Data de Ingresso nao Informado.";
         $this->erro_campo = "ed75_d_ingresso_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed75_d_ingresso_dia"])){ 
         $sql  .= $virgula." ed75_d_ingresso = null ";
         $virgula = ",";
         if(trim($this->ed75_d_ingresso) == null ){ 
           $this->erro_sql = " Campo Data de Ingresso nao Informado.";
           $this->erro_campo = "ed75_d_ingresso_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed75_c_simultaneo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed75_c_simultaneo"])){ 
       $sql  .= $virgula." ed75_c_simultaneo = '$this->ed75_c_simultaneo' ";
       $virgula = ",";
       if(trim($this->ed75_c_simultaneo) == null ){ 
         $this->erro_sql = " Campo Atende Simultaneamente nao Informado.";
         $this->erro_campo = "ed75_c_simultaneo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed75_i_saidaescola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed75_i_saidaescola_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed75_i_saidaescola_dia"] !="") ){ 
       $sql  .= $virgula." ed75_i_saidaescola = '$this->ed75_i_saidaescola' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed75_i_saidaescola_dia"])){ 
         $sql  .= $virgula." ed75_i_saidaescola = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($ed75_i_codigo!=null){
       $sql .= " ed75_i_codigo = $this->ed75_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed75_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008536,'$this->ed75_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed75_i_codigo"]) || $this->ed75_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1010094,1008536,'".AddSlashes(pg_result($resaco,$conresaco,'ed75_i_codigo'))."','$this->ed75_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed75_i_escola"]) || $this->ed75_i_escola != "")
           $resac = db_query("insert into db_acount values($acount,1010094,1008537,'".AddSlashes(pg_result($resaco,$conresaco,'ed75_i_escola'))."','$this->ed75_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed75_i_rechumano"]) || $this->ed75_i_rechumano != "")
           $resac = db_query("insert into db_acount values($acount,1010094,1008538,'".AddSlashes(pg_result($resaco,$conresaco,'ed75_i_rechumano'))."','$this->ed75_i_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed75_d_ingresso"]) || $this->ed75_d_ingresso != "")
           $resac = db_query("insert into db_acount values($acount,1010094,1008564,'".AddSlashes(pg_result($resaco,$conresaco,'ed75_d_ingresso'))."','$this->ed75_d_ingresso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed75_c_simultaneo"]) || $this->ed75_c_simultaneo != "")
           $resac = db_query("insert into db_acount values($acount,1010094,17612,'".AddSlashes(pg_result($resaco,$conresaco,'ed75_c_simultaneo'))."','$this->ed75_c_simultaneo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed75_i_saidaescola"]) || $this->ed75_i_saidaescola != "")
           $resac = db_query("insert into db_acount values($acount,1010094,19748,'".AddSlashes(pg_result($resaco,$conresaco,'ed75_i_saidaescola'))."','$this->ed75_i_saidaescola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liga��o do RH �s escolas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed75_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liga��o do RH �s escolas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed75_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed75_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed75_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed75_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008536,'$ed75_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010094,1008536,'','".AddSlashes(pg_result($resaco,$iresaco,'ed75_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010094,1008537,'','".AddSlashes(pg_result($resaco,$iresaco,'ed75_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010094,1008538,'','".AddSlashes(pg_result($resaco,$iresaco,'ed75_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010094,1008564,'','".AddSlashes(pg_result($resaco,$iresaco,'ed75_d_ingresso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010094,17612,'','".AddSlashes(pg_result($resaco,$iresaco,'ed75_c_simultaneo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010094,19748,'','".AddSlashes(pg_result($resaco,$iresaco,'ed75_i_saidaescola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rechumanoescola
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed75_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed75_i_codigo = $ed75_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liga��o do RH �s escolas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed75_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liga��o do RH �s escolas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed75_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed75_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:rechumanoescola";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed75_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rechumanoescola ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = rechumanoescola.ed75_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = rechumanoescola.ed75_i_rechumano";
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($ed75_i_codigo!=null ){
         $sql2 .= " where rechumanoescola.ed75_i_codigo = $ed75_i_codigo "; 
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
   function sql_query_file ( $ed75_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rechumanoescola ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed75_i_codigo!=null ){
         $sql2 .= " where rechumanoescola.ed75_i_codigo = $ed75_i_codigo "; 
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