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
//CLASSE DA ENTIDADE formaavaliacao
class cl_formaavaliacao { 
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
   var $ed37_i_codigo = 0; 
   var $ed37_c_descr = null; 
   var $ed37_c_tipo = null; 
   var $ed37_i_menorvalor = 0; 
   var $ed37_i_maiorvalor = 0; 
   var $ed37_i_variacao = 0; 
   var $ed37_c_minimoaprov = null; 
   var $ed37_c_parecerarmaz = null; 
   var $ed37_i_escola = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed37_i_codigo = int8 = C�digo 
                 ed37_c_descr = char(30) = Descri��o 
                 ed37_c_tipo = char(10) = Tipo de Resultado 
                 ed37_i_menorvalor = float4 = Menor Nota 
                 ed37_i_maiorvalor = float4 = Maior Nota 
                 ed37_i_variacao = float4 = Varia��o 
                 ed37_c_minimoaprov = char(10) = M�nimo para Aprova��o 
                 ed37_c_parecerarmaz = char(1) = Parecer Armazenado 
                 ed37_i_escola = int8 = Escola 
                 ";
   //funcao construtor da classe 
   function cl_formaavaliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("formaavaliacao"); 
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
       $this->ed37_i_codigo = ($this->ed37_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_i_codigo"]:$this->ed37_i_codigo);
       $this->ed37_c_descr = ($this->ed37_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_c_descr"]:$this->ed37_c_descr);
       $this->ed37_c_tipo = ($this->ed37_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_c_tipo"]:$this->ed37_c_tipo);
       $this->ed37_i_menorvalor = ($this->ed37_i_menorvalor == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_i_menorvalor"]:$this->ed37_i_menorvalor);
       $this->ed37_i_maiorvalor = ($this->ed37_i_maiorvalor == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_i_maiorvalor"]:$this->ed37_i_maiorvalor);
       $this->ed37_i_variacao = ($this->ed37_i_variacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_i_variacao"]:$this->ed37_i_variacao);
       $this->ed37_c_minimoaprov = ($this->ed37_c_minimoaprov == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_c_minimoaprov"]:$this->ed37_c_minimoaprov);
       $this->ed37_c_parecerarmaz = ($this->ed37_c_parecerarmaz == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_c_parecerarmaz"]:$this->ed37_c_parecerarmaz);
       $this->ed37_i_escola = ($this->ed37_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_i_escola"]:$this->ed37_i_escola);
     }else{
       $this->ed37_i_codigo = ($this->ed37_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_i_codigo"]:$this->ed37_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed37_i_codigo){ 
      $this->atualizacampos();
     if($this->ed37_c_descr == null ){ 
       $this->erro_sql = " Campo Descri��o nao Informado.";
       $this->erro_campo = "ed37_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed37_c_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Resultado nao Informado.";
       $this->erro_campo = "ed37_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed37_i_menorvalor == null ){ 
       $this->erro_sql = " Campo Menor Nota nao Informado.";
       $this->erro_campo = "ed37_i_menorvalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed37_i_maiorvalor == null ){ 
       $this->erro_sql = " Campo Maior Nota nao Informado.";
       $this->erro_campo = "ed37_i_maiorvalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed37_i_variacao == null ){ 
       $this->erro_sql = " Campo Varia��o nao Informado.";
       $this->erro_campo = "ed37_i_variacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed37_c_minimoaprov == null ){ 
       $this->erro_sql = " Campo M�nimo para Aprova��o nao Informado.";
       $this->erro_campo = "ed37_c_minimoaprov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed37_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed37_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed37_i_codigo == "" || $ed37_i_codigo == null ){
       $result = db_query("select nextval('formaavaliacao_ed37_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: formaavaliacao_ed37_i_codigo_seq do campo: ed37_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed37_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from formaavaliacao_ed37_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed37_i_codigo)){
         $this->erro_sql = " Campo ed37_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed37_i_codigo = $ed37_i_codigo; 
       }
     }
     if(($this->ed37_i_codigo == null) || ($this->ed37_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed37_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into formaavaliacao(
                                       ed37_i_codigo 
                                      ,ed37_c_descr 
                                      ,ed37_c_tipo 
                                      ,ed37_i_menorvalor 
                                      ,ed37_i_maiorvalor 
                                      ,ed37_i_variacao 
                                      ,ed37_c_minimoaprov 
                                      ,ed37_c_parecerarmaz 
                                      ,ed37_i_escola 
                       )
                values (
                                $this->ed37_i_codigo 
                               ,'$this->ed37_c_descr' 
                               ,'$this->ed37_c_tipo' 
                               ,$this->ed37_i_menorvalor 
                               ,$this->ed37_i_maiorvalor 
                               ,$this->ed37_i_variacao 
                               ,'$this->ed37_c_minimoaprov' 
                               ,'$this->ed37_c_parecerarmaz' 
                               ,$this->ed37_i_escola 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Formas de Avalia��es ($this->ed37_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Formas de Avalia��es j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Formas de Avalia��es ($this->ed37_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed37_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed37_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008420,'$this->ed37_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010071,1008420,'','".AddSlashes(pg_result($resaco,0,'ed37_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010071,1008421,'','".AddSlashes(pg_result($resaco,0,'ed37_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010071,1008422,'','".AddSlashes(pg_result($resaco,0,'ed37_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010071,1008423,'','".AddSlashes(pg_result($resaco,0,'ed37_i_menorvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010071,1008424,'','".AddSlashes(pg_result($resaco,0,'ed37_i_maiorvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010071,1008425,'','".AddSlashes(pg_result($resaco,0,'ed37_i_variacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010071,1008426,'','".AddSlashes(pg_result($resaco,0,'ed37_c_minimoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010071,1008427,'','".AddSlashes(pg_result($resaco,0,'ed37_c_parecerarmaz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010071,1009219,'','".AddSlashes(pg_result($resaco,0,'ed37_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed37_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update formaavaliacao set ";
     $virgula = "";
     if(trim($this->ed37_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_codigo"])){ 
       $sql  .= $virgula." ed37_i_codigo = $this->ed37_i_codigo ";
       $virgula = ",";
       if(trim($this->ed37_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed37_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_descr"])){ 
       $sql  .= $virgula." ed37_c_descr = '$this->ed37_c_descr' ";
       $virgula = ",";
       if(trim($this->ed37_c_descr) == null ){ 
         $this->erro_sql = " Campo Descri��o nao Informado.";
         $this->erro_campo = "ed37_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_tipo"])){ 
       $sql  .= $virgula." ed37_c_tipo = '$this->ed37_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed37_c_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Resultado nao Informado.";
         $this->erro_campo = "ed37_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_i_menorvalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_menorvalor"])){ 
       $sql  .= $virgula." ed37_i_menorvalor = $this->ed37_i_menorvalor ";
       $virgula = ",";
       if(trim($this->ed37_i_menorvalor) == null ){ 
         $this->erro_sql = " Campo Menor Nota nao Informado.";
         $this->erro_campo = "ed37_i_menorvalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_i_maiorvalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_maiorvalor"])){ 
       $sql  .= $virgula." ed37_i_maiorvalor = $this->ed37_i_maiorvalor ";
       $virgula = ",";
       if(trim($this->ed37_i_maiorvalor) == null ){ 
         $this->erro_sql = " Campo Maior Nota nao Informado.";
         $this->erro_campo = "ed37_i_maiorvalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_i_variacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_variacao"])){ 
       $sql  .= $virgula." ed37_i_variacao = $this->ed37_i_variacao ";
       $virgula = ",";
       if(trim($this->ed37_i_variacao) == null ){ 
         $this->erro_sql = " Campo Varia��o nao Informado.";
         $this->erro_campo = "ed37_i_variacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_c_minimoaprov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_minimoaprov"])){ 
       $sql  .= $virgula." ed37_c_minimoaprov = '$this->ed37_c_minimoaprov' ";
       $virgula = ",";
       if(trim($this->ed37_c_minimoaprov) == null ){ 
         $this->erro_sql = " Campo M�nimo para Aprova��o nao Informado.";
         $this->erro_campo = "ed37_c_minimoaprov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_c_parecerarmaz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_parecerarmaz"])){ 
       $sql  .= $virgula." ed37_c_parecerarmaz = '$this->ed37_c_parecerarmaz' ";
       $virgula = ",";
     }
     if(trim($this->ed37_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_escola"])){ 
       $sql  .= $virgula." ed37_i_escola = $this->ed37_i_escola ";
       $virgula = ",";
       if(trim($this->ed37_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed37_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed37_i_codigo!=null){
       $sql .= " ed37_i_codigo = $this->ed37_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed37_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008420,'$this->ed37_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010071,1008420,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_i_codigo'))."','$this->ed37_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_descr"]))
           $resac = db_query("insert into db_acount values($acount,1010071,1008421,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_c_descr'))."','$this->ed37_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1010071,1008422,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_c_tipo'))."','$this->ed37_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_menorvalor"]))
           $resac = db_query("insert into db_acount values($acount,1010071,1008423,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_i_menorvalor'))."','$this->ed37_i_menorvalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_maiorvalor"]))
           $resac = db_query("insert into db_acount values($acount,1010071,1008424,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_i_maiorvalor'))."','$this->ed37_i_maiorvalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_variacao"]))
           $resac = db_query("insert into db_acount values($acount,1010071,1008425,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_i_variacao'))."','$this->ed37_i_variacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_minimoaprov"]))
           $resac = db_query("insert into db_acount values($acount,1010071,1008426,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_c_minimoaprov'))."','$this->ed37_c_minimoaprov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_parecerarmaz"]))
           $resac = db_query("insert into db_acount values($acount,1010071,1008427,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_c_parecerarmaz'))."','$this->ed37_c_parecerarmaz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,1010071,1009219,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_i_escola'))."','$this->ed37_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Formas de Avalia��es nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed37_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Formas de Avalia��es nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed37_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed37_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed37_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed37_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008420,'$ed37_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010071,1008420,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008421,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008422,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008423,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_i_menorvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008424,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_i_maiorvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008425,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_i_variacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008426,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_c_minimoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008427,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_c_parecerarmaz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1009219,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from formaavaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed37_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed37_i_codigo = $ed37_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Formas de Avalia��es nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed37_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Formas de Avalia��es nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed37_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed37_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:formaavaliacao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed37_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from formaavaliacao ";
     $sql .= "      left join conceito  on  conceito.ed39_i_formaavaliacao = formaavaliacao.ed37_i_codigo";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = formaavaliacao.ed37_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed37_i_codigo!=null ){
         $sql2 .= " where formaavaliacao.ed37_i_codigo = $ed37_i_codigo "; 
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
   function sql_query_file ( $ed37_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from formaavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed37_i_codigo!=null ){
         $sql2 .= " where formaavaliacao.ed37_i_codigo = $ed37_i_codigo "; 
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