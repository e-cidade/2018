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

//MODULO: TFD
//CLASSE DA ENTIDADE tfd_gradehorarios
class cl_tfd_gradehorarios { 
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
   var $tf02_i_codigo = 0; 
   var $tf02_i_destino = 0; 
   var $tf02_i_diasemana = 0; 
   var $tf02_i_lotacao = 0; 
   var $tf02_c_horario = null; 
   var $tf02_d_validadeini_dia = null; 
   var $tf02_d_validadeini_mes = null; 
   var $tf02_d_validadeini_ano = null; 
   var $tf02_d_validadeini = null; 
   var $tf02_d_validadefim_dia = null; 
   var $tf02_d_validadefim_mes = null; 
   var $tf02_d_validadefim_ano = null; 
   var $tf02_d_validadefim = null; 
   var $tf02_c_localsaida = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf02_i_codigo = int4 = C�digo 
                 tf02_i_destino = int4 = Destino 
                 tf02_i_diasemana = int4 = Dia Semana 
                 tf02_i_lotacao = int4 = Lota��o 
                 tf02_c_horario = char(5) = Hor�rio 
                 tf02_d_validadeini = date = In�cio 
                 tf02_d_validadefim = date = Fim 
                 tf02_c_localsaida = varchar(50) = Local da Sa�da 
                 ";
   //funcao construtor da classe 
   function cl_tfd_gradehorarios() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_gradehorarios"); 
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
       $this->tf02_i_codigo = ($this->tf02_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_i_codigo"]:$this->tf02_i_codigo);
       $this->tf02_i_destino = ($this->tf02_i_destino == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_i_destino"]:$this->tf02_i_destino);
       $this->tf02_i_diasemana = ($this->tf02_i_diasemana == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_i_diasemana"]:$this->tf02_i_diasemana);
       $this->tf02_i_lotacao = ($this->tf02_i_lotacao == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_i_lotacao"]:$this->tf02_i_lotacao);
       $this->tf02_c_horario = ($this->tf02_c_horario == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_c_horario"]:$this->tf02_c_horario);
       if($this->tf02_d_validadeini == ""){
         $this->tf02_d_validadeini_dia = ($this->tf02_d_validadeini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_d_validadeini_dia"]:$this->tf02_d_validadeini_dia);
         $this->tf02_d_validadeini_mes = ($this->tf02_d_validadeini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_d_validadeini_mes"]:$this->tf02_d_validadeini_mes);
         $this->tf02_d_validadeini_ano = ($this->tf02_d_validadeini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_d_validadeini_ano"]:$this->tf02_d_validadeini_ano);
         if($this->tf02_d_validadeini_dia != ""){
            $this->tf02_d_validadeini = $this->tf02_d_validadeini_ano."-".$this->tf02_d_validadeini_mes."-".$this->tf02_d_validadeini_dia;
         }
       }
       if($this->tf02_d_validadefim == ""){
         $this->tf02_d_validadefim_dia = ($this->tf02_d_validadefim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_d_validadefim_dia"]:$this->tf02_d_validadefim_dia);
         $this->tf02_d_validadefim_mes = ($this->tf02_d_validadefim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_d_validadefim_mes"]:$this->tf02_d_validadefim_mes);
         $this->tf02_d_validadefim_ano = ($this->tf02_d_validadefim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_d_validadefim_ano"]:$this->tf02_d_validadefim_ano);
         if($this->tf02_d_validadefim_dia != ""){
            $this->tf02_d_validadefim = $this->tf02_d_validadefim_ano."-".$this->tf02_d_validadefim_mes."-".$this->tf02_d_validadefim_dia;
         }
       }
       $this->tf02_c_localsaida = ($this->tf02_c_localsaida == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_c_localsaida"]:$this->tf02_c_localsaida);
     }else{
       $this->tf02_i_codigo = ($this->tf02_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf02_i_codigo"]:$this->tf02_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf02_i_codigo){ 
      $this->atualizacampos();
     if($this->tf02_i_destino == null ){ 
       $this->erro_sql = " Campo Destino nao Informado.";
       $this->erro_campo = "tf02_i_destino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf02_i_diasemana == null ){ 
       $this->erro_sql = " Campo Dia Semana nao Informado.";
       $this->erro_campo = "tf02_i_diasemana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf02_i_lotacao == null ){ 
       $this->erro_sql = " Campo Lota��o nao Informado.";
       $this->erro_campo = "tf02_i_lotacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf02_c_horario == null ){ 
       $this->erro_sql = " Campo Hor�rio nao Informado.";
       $this->erro_campo = "tf02_c_horario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf02_d_validadeini == null ){ 
       $this->erro_sql = " Campo In�cio nao Informado.";
       $this->erro_campo = "tf02_d_validadeini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf02_d_validadefim == null ){ 
       $this->tf02_d_validadefim = "null";
     }
     if($this->tf02_c_localsaida == null ){ 
       $this->erro_sql = " Campo Local da Sa�da nao Informado.";
       $this->erro_campo = "tf02_c_localsaida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf02_i_codigo == "" || $tf02_i_codigo == null ){
       $result = db_query("select nextval('tfd_gradehorarios_tf02_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_gradehorarios_tf02_i_codigo_seq do campo: tf02_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf02_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tfd_gradehorarios_tf02_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf02_i_codigo)){
         $this->erro_sql = " Campo tf02_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf02_i_codigo = $tf02_i_codigo; 
       }
     }
     if(($this->tf02_i_codigo == null) || ($this->tf02_i_codigo == "") ){ 
       $this->erro_sql = " Campo tf02_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_gradehorarios(
                                       tf02_i_codigo 
                                      ,tf02_i_destino 
                                      ,tf02_i_diasemana 
                                      ,tf02_i_lotacao 
                                      ,tf02_c_horario 
                                      ,tf02_d_validadeini 
                                      ,tf02_d_validadefim 
                                      ,tf02_c_localsaida 
                       )
                values (
                                $this->tf02_i_codigo 
                               ,$this->tf02_i_destino 
                               ,$this->tf02_i_diasemana 
                               ,$this->tf02_i_lotacao 
                               ,'$this->tf02_c_horario' 
                               ,".($this->tf02_d_validadeini == "null" || $this->tf02_d_validadeini == ""?"null":"'".$this->tf02_d_validadeini."'")." 
                               ,".($this->tf02_d_validadefim == "null" || $this->tf02_d_validadefim == ""?"null":"'".$this->tf02_d_validadefim."'")." 
                               ,'$this->tf02_c_localsaida' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_gradehorarios ($this->tf02_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_gradehorarios j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_gradehorarios ($this->tf02_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf02_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf02_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16333,'$this->tf02_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2858,16333,'','".AddSlashes(pg_result($resaco,0,'tf02_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2858,16335,'','".AddSlashes(pg_result($resaco,0,'tf02_i_destino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2858,16334,'','".AddSlashes(pg_result($resaco,0,'tf02_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2858,16336,'','".AddSlashes(pg_result($resaco,0,'tf02_i_lotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2858,16337,'','".AddSlashes(pg_result($resaco,0,'tf02_c_horario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2858,16338,'','".AddSlashes(pg_result($resaco,0,'tf02_d_validadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2858,16339,'','".AddSlashes(pg_result($resaco,0,'tf02_d_validadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2858,16340,'','".AddSlashes(pg_result($resaco,0,'tf02_c_localsaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf02_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tfd_gradehorarios set ";
     $virgula = "";
     if(trim($this->tf02_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf02_i_codigo"])){ 
       $sql  .= $virgula." tf02_i_codigo = $this->tf02_i_codigo ";
       $virgula = ",";
       if(trim($this->tf02_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "tf02_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf02_i_destino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf02_i_destino"])){ 
       $sql  .= $virgula." tf02_i_destino = $this->tf02_i_destino ";
       $virgula = ",";
       if(trim($this->tf02_i_destino) == null ){ 
         $this->erro_sql = " Campo Destino nao Informado.";
         $this->erro_campo = "tf02_i_destino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf02_i_diasemana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf02_i_diasemana"])){ 
       $sql  .= $virgula." tf02_i_diasemana = $this->tf02_i_diasemana ";
       $virgula = ",";
       if(trim($this->tf02_i_diasemana) == null ){ 
         $this->erro_sql = " Campo Dia Semana nao Informado.";
         $this->erro_campo = "tf02_i_diasemana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf02_i_lotacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf02_i_lotacao"])){ 
       $sql  .= $virgula." tf02_i_lotacao = $this->tf02_i_lotacao ";
       $virgula = ",";
       if(trim($this->tf02_i_lotacao) == null ){ 
         $this->erro_sql = " Campo Lota��o nao Informado.";
         $this->erro_campo = "tf02_i_lotacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf02_c_horario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf02_c_horario"])){ 
       $sql  .= $virgula." tf02_c_horario = '$this->tf02_c_horario' ";
       $virgula = ",";
       if(trim($this->tf02_c_horario) == null ){ 
         $this->erro_sql = " Campo Hor�rio nao Informado.";
         $this->erro_campo = "tf02_c_horario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf02_d_validadeini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf02_d_validadeini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf02_d_validadeini_dia"] !="") ){ 
       $sql  .= $virgula." tf02_d_validadeini = '$this->tf02_d_validadeini' ";
       $virgula = ",";
       if(trim($this->tf02_d_validadeini) == null ){ 
         $this->erro_sql = " Campo In�cio nao Informado.";
         $this->erro_campo = "tf02_d_validadeini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf02_d_validadeini_dia"])){ 
         $sql  .= $virgula." tf02_d_validadeini = null ";
         $virgula = ",";
         if(trim($this->tf02_d_validadeini) == null ){ 
           $this->erro_sql = " Campo In�cio nao Informado.";
           $this->erro_campo = "tf02_d_validadeini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf02_d_validadefim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf02_d_validadefim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf02_d_validadefim_dia"] !="") ){ 
       $sql  .= $virgula." tf02_d_validadefim = '$this->tf02_d_validadefim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf02_d_validadefim_dia"])){ 
         $sql  .= $virgula." tf02_d_validadefim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->tf02_c_localsaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf02_c_localsaida"])){ 
       $sql  .= $virgula." tf02_c_localsaida = '$this->tf02_c_localsaida' ";
       $virgula = ",";
       if(trim($this->tf02_c_localsaida) == null ){ 
         $this->erro_sql = " Campo Local da Sa�da nao Informado.";
         $this->erro_campo = "tf02_c_localsaida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf02_i_codigo!=null){
       $sql .= " tf02_i_codigo = $this->tf02_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf02_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16333,'$this->tf02_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf02_i_codigo"]) || $this->tf02_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2858,16333,'".AddSlashes(pg_result($resaco,$conresaco,'tf02_i_codigo'))."','$this->tf02_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf02_i_destino"]) || $this->tf02_i_destino != "")
           $resac = db_query("insert into db_acount values($acount,2858,16335,'".AddSlashes(pg_result($resaco,$conresaco,'tf02_i_destino'))."','$this->tf02_i_destino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf02_i_diasemana"]) || $this->tf02_i_diasemana != "")
           $resac = db_query("insert into db_acount values($acount,2858,16334,'".AddSlashes(pg_result($resaco,$conresaco,'tf02_i_diasemana'))."','$this->tf02_i_diasemana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf02_i_lotacao"]) || $this->tf02_i_lotacao != "")
           $resac = db_query("insert into db_acount values($acount,2858,16336,'".AddSlashes(pg_result($resaco,$conresaco,'tf02_i_lotacao'))."','$this->tf02_i_lotacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf02_c_horario"]) || $this->tf02_c_horario != "")
           $resac = db_query("insert into db_acount values($acount,2858,16337,'".AddSlashes(pg_result($resaco,$conresaco,'tf02_c_horario'))."','$this->tf02_c_horario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf02_d_validadeini"]) || $this->tf02_d_validadeini != "")
           $resac = db_query("insert into db_acount values($acount,2858,16338,'".AddSlashes(pg_result($resaco,$conresaco,'tf02_d_validadeini'))."','$this->tf02_d_validadeini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf02_d_validadefim"]) || $this->tf02_d_validadefim != "")
           $resac = db_query("insert into db_acount values($acount,2858,16339,'".AddSlashes(pg_result($resaco,$conresaco,'tf02_d_validadefim'))."','$this->tf02_d_validadefim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf02_c_localsaida"]) || $this->tf02_c_localsaida != "")
           $resac = db_query("insert into db_acount values($acount,2858,16340,'".AddSlashes(pg_result($resaco,$conresaco,'tf02_c_localsaida'))."','$this->tf02_c_localsaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_gradehorarios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf02_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_gradehorarios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf02_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf02_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf02_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf02_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16333,'$tf02_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2858,16333,'','".AddSlashes(pg_result($resaco,$iresaco,'tf02_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2858,16335,'','".AddSlashes(pg_result($resaco,$iresaco,'tf02_i_destino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2858,16334,'','".AddSlashes(pg_result($resaco,$iresaco,'tf02_i_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2858,16336,'','".AddSlashes(pg_result($resaco,$iresaco,'tf02_i_lotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2858,16337,'','".AddSlashes(pg_result($resaco,$iresaco,'tf02_c_horario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2858,16338,'','".AddSlashes(pg_result($resaco,$iresaco,'tf02_d_validadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2858,16339,'','".AddSlashes(pg_result($resaco,$iresaco,'tf02_d_validadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2858,16340,'','".AddSlashes(pg_result($resaco,$iresaco,'tf02_c_localsaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_gradehorarios
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf02_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf02_i_codigo = $tf02_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_gradehorarios nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf02_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_gradehorarios nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf02_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf02_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_gradehorarios";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf02_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_gradehorarios ";
     $sql .= "      inner join tfd_destino  on  tfd_destino.tf03_i_codigo = tfd_gradehorarios.tf02_i_destino";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = tfd_gradehorarios.tf02_i_diasemana";
     $sql .= "      inner join tfd_tipodistancia  on  tfd_tipodistancia.tf24_i_codigo = tfd_destino.tf03_i_tipodistancia";
     $sql2 = "";
     if($dbwhere==""){
       if($tf02_i_codigo!=null ){
         $sql2 .= " where tfd_gradehorarios.tf02_i_codigo = $tf02_i_codigo "; 
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
   function sql_query_file ( $tf02_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_gradehorarios ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf02_i_codigo!=null ){
         $sql2 .= " where tfd_gradehorarios.tf02_i_codigo = $tf02_i_codigo "; 
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