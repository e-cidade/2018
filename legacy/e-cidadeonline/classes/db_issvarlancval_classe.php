<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: issqn
//CLASSE DA ENTIDADE issvarlancval
class cl_issvarlancval { 
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
   var $q50_seq = 0; 
   var $q50_codigo = 0; 
   var $q50_numpre = 0; 
   var $q50_numpar = 0; 
   var $q50_valor = 0; 
   var $q50_ano = 0; 
   var $q50_mes = 0; 
   var $q50_histor = null; 
   var $q50_aliq = 0; 
   var $q50_bruto = 0; 
   var $q50_vlrinf = 0; 
   var $q50_ip = null; 
   var $q50_data_dia = null; 
   var $q50_data_mes = null; 
   var $q50_data_ano = null; 
   var $q50_data = null; 
   var $q50_hora = null; 
   var $q50_arquivo = null; 
   var $q50_idusuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q50_seq = int8 = Sequência 
                 q50_codigo = int8 = Código da arrecadacao de issqn 
                 q50_numpre = int4 = Numpre 
                 q50_numpar = int4 = Numpar 
                 q50_valor = float8 = Valor 
                 q50_ano = int4 = Ano 
                 q50_mes = int4 = Mes 
                 q50_histor = text = Histórico 
                 q50_aliq = float8 = Alíquota 
                 q50_bruto = float8 = Valor Bruto 
                 q50_vlrinf = float8 = Valor Informado 
                 q50_ip = varchar(50) = log do IP 
                 q50_data = date = Data do Log 
                 q50_hora = varchar(10) = Hora do Log 
                 q50_arquivo = text = Arquivo do Log 
                 q50_idusuario = int4 = Usuário log 
                 ";
   //funcao construtor da classe 
   function cl_issvarlancval() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issvarlancval"); 
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
       $this->q50_seq = ($this->q50_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_seq"]:$this->q50_seq);
       $this->q50_codigo = ($this->q50_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_codigo"]:$this->q50_codigo);
       $this->q50_numpre = ($this->q50_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_numpre"]:$this->q50_numpre);
       $this->q50_numpar = ($this->q50_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_numpar"]:$this->q50_numpar);
       $this->q50_valor = ($this->q50_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_valor"]:$this->q50_valor);
       $this->q50_ano = ($this->q50_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_ano"]:$this->q50_ano);
       $this->q50_mes = ($this->q50_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_mes"]:$this->q50_mes);
       $this->q50_histor = ($this->q50_histor == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_histor"]:$this->q50_histor);
       $this->q50_aliq = ($this->q50_aliq == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_aliq"]:$this->q50_aliq);
       $this->q50_bruto = ($this->q50_bruto == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_bruto"]:$this->q50_bruto);
       $this->q50_vlrinf = ($this->q50_vlrinf == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_vlrinf"]:$this->q50_vlrinf);
       $this->q50_ip = ($this->q50_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_ip"]:$this->q50_ip);
       if($this->q50_data == ""){
         $this->q50_data_dia = ($this->q50_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_data_dia"]:$this->q50_data_dia);
         $this->q50_data_mes = ($this->q50_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_data_mes"]:$this->q50_data_mes);
         $this->q50_data_ano = ($this->q50_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_data_ano"]:$this->q50_data_ano);
         if($this->q50_data_dia != ""){
            $this->q50_data = $this->q50_data_ano."-".$this->q50_data_mes."-".$this->q50_data_dia;
         }
       }
       $this->q50_hora = ($this->q50_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_hora"]:$this->q50_hora);
       $this->q50_arquivo = ($this->q50_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_arquivo"]:$this->q50_arquivo);
       $this->q50_idusuario = ($this->q50_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_idusuario"]:$this->q50_idusuario);
     }else{
       $this->q50_seq = ($this->q50_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["q50_seq"]:$this->q50_seq);
     }
   }
   // funcao para inclusao
   function incluir ($q50_seq){ 
      $this->atualizacampos();
     if($this->q50_codigo == null ){ 
       $this->erro_sql = " Campo Código da arrecadacao de issqn nao Informado.";
       $this->erro_campo = "q50_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "q50_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_numpar == null ){ 
       $this->erro_sql = " Campo Numpar nao Informado.";
       $this->erro_campo = "q50_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "q50_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "q50_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_mes == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "q50_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_aliq == null ){ 
       $this->erro_sql = " Campo Alíquota nao Informado.";
       $this->erro_campo = "q50_aliq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_bruto == null ){ 
       $this->erro_sql = " Campo Valor Bruto nao Informado.";
       $this->erro_campo = "q50_bruto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_vlrinf == null ){ 
       $this->erro_sql = " Campo Valor Informado nao Informado.";
       $this->erro_campo = "q50_vlrinf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_ip == null ){ 
       $this->erro_sql = " Campo log do IP nao Informado.";
       $this->erro_campo = "q50_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_data == null ){ 
       $this->erro_sql = " Campo Data do Log nao Informado.";
       $this->erro_campo = "q50_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_hora == null ){ 
       $this->erro_sql = " Campo Hora do Log nao Informado.";
       $this->erro_campo = "q50_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_arquivo == null ){ 
       $this->erro_sql = " Campo Arquivo do Log nao Informado.";
       $this->erro_campo = "q50_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q50_idusuario == null ){ 
       $this->q50_idusuario = "0";
     }
     if($q50_seq == "" || $q50_seq == null ){
       $result = db_query("select nextval('issvarlancval_q50_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issvarlancval_q50_seq_seq do campo: q50_seq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q50_seq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issvarlancval_q50_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $q50_seq)){
         $this->erro_sql = " Campo q50_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q50_seq = $q50_seq; 
       }
     }
     if(($this->q50_seq == null) || ($this->q50_seq == "") ){ 
       $this->erro_sql = " Campo q50_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issvarlancval(
                                       q50_seq 
                                      ,q50_codigo 
                                      ,q50_numpre 
                                      ,q50_numpar 
                                      ,q50_valor 
                                      ,q50_ano 
                                      ,q50_mes 
                                      ,q50_histor 
                                      ,q50_aliq 
                                      ,q50_bruto 
                                      ,q50_vlrinf 
                                      ,q50_ip 
                                      ,q50_data 
                                      ,q50_hora 
                                      ,q50_arquivo 
                                      ,q50_idusuario 
                       )
                values (
                                $this->q50_seq 
                               ,$this->q50_codigo 
                               ,$this->q50_numpre 
                               ,$this->q50_numpar 
                               ,$this->q50_valor 
                               ,$this->q50_ano 
                               ,$this->q50_mes 
                               ,'$this->q50_histor' 
                               ,$this->q50_aliq 
                               ,$this->q50_bruto 
                               ,$this->q50_vlrinf 
                               ,'$this->q50_ip' 
                               ,".($this->q50_data == "null" || $this->q50_data == ""?"null":"'".$this->q50_data."'")." 
                               ,'$this->q50_hora' 
                               ,'$this->q50_arquivo' 
                               ,$this->q50_idusuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q50_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q50_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q50_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q50_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8711,'$this->q50_seq','I')");
       $resac = db_query("insert into db_acount values($acount,1486,8711,'','".AddSlashes(pg_result($resaco,0,'q50_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8712,'','".AddSlashes(pg_result($resaco,0,'q50_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8713,'','".AddSlashes(pg_result($resaco,0,'q50_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8714,'','".AddSlashes(pg_result($resaco,0,'q50_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8715,'','".AddSlashes(pg_result($resaco,0,'q50_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8716,'','".AddSlashes(pg_result($resaco,0,'q50_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8717,'','".AddSlashes(pg_result($resaco,0,'q50_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8718,'','".AddSlashes(pg_result($resaco,0,'q50_histor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8719,'','".AddSlashes(pg_result($resaco,0,'q50_aliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8720,'','".AddSlashes(pg_result($resaco,0,'q50_bruto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8721,'','".AddSlashes(pg_result($resaco,0,'q50_vlrinf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8722,'','".AddSlashes(pg_result($resaco,0,'q50_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8723,'','".AddSlashes(pg_result($resaco,0,'q50_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8724,'','".AddSlashes(pg_result($resaco,0,'q50_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8725,'','".AddSlashes(pg_result($resaco,0,'q50_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1486,8740,'','".AddSlashes(pg_result($resaco,0,'q50_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q50_seq=null) { 
      $this->atualizacampos();
     $sql = " update issvarlancval set ";
     $virgula = "";
     if(trim($this->q50_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_seq"])){ 
       $sql  .= $virgula." q50_seq = $this->q50_seq ";
       $virgula = ",";
       if(trim($this->q50_seq) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "q50_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_codigo"])){ 
       $sql  .= $virgula." q50_codigo = $this->q50_codigo ";
       $virgula = ",";
       if(trim($this->q50_codigo) == null ){ 
         $this->erro_sql = " Campo Código da arrecadacao de issqn nao Informado.";
         $this->erro_campo = "q50_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_numpre"])){ 
       $sql  .= $virgula." q50_numpre = $this->q50_numpre ";
       $virgula = ",";
       if(trim($this->q50_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "q50_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_numpar"])){ 
       $sql  .= $virgula." q50_numpar = $this->q50_numpar ";
       $virgula = ",";
       if(trim($this->q50_numpar) == null ){ 
         $this->erro_sql = " Campo Numpar nao Informado.";
         $this->erro_campo = "q50_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_valor"])){ 
       $sql  .= $virgula." q50_valor = $this->q50_valor ";
       $virgula = ",";
       if(trim($this->q50_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "q50_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_ano"])){ 
       $sql  .= $virgula." q50_ano = $this->q50_ano ";
       $virgula = ",";
       if(trim($this->q50_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "q50_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_mes"])){ 
       $sql  .= $virgula." q50_mes = $this->q50_mes ";
       $virgula = ",";
       if(trim($this->q50_mes) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "q50_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_histor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_histor"])){ 
       $sql  .= $virgula." q50_histor = '$this->q50_histor' ";
       $virgula = ",";
     }
     if(trim($this->q50_aliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_aliq"])){ 
       $sql  .= $virgula." q50_aliq = $this->q50_aliq ";
       $virgula = ",";
       if(trim($this->q50_aliq) == null ){ 
         $this->erro_sql = " Campo Alíquota nao Informado.";
         $this->erro_campo = "q50_aliq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_bruto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_bruto"])){ 
       $sql  .= $virgula." q50_bruto = $this->q50_bruto ";
       $virgula = ",";
       if(trim($this->q50_bruto) == null ){ 
         $this->erro_sql = " Campo Valor Bruto nao Informado.";
         $this->erro_campo = "q50_bruto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_vlrinf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_vlrinf"])){ 
       $sql  .= $virgula." q50_vlrinf = $this->q50_vlrinf ";
       $virgula = ",";
       if(trim($this->q50_vlrinf) == null ){ 
         $this->erro_sql = " Campo Valor Informado nao Informado.";
         $this->erro_campo = "q50_vlrinf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_ip"])){ 
       $sql  .= $virgula." q50_ip = '$this->q50_ip' ";
       $virgula = ",";
       if(trim($this->q50_ip) == null ){ 
         $this->erro_sql = " Campo log do IP nao Informado.";
         $this->erro_campo = "q50_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q50_data_dia"] !="") ){ 
       $sql  .= $virgula." q50_data = '$this->q50_data' ";
       $virgula = ",";
       if(trim($this->q50_data) == null ){ 
         $this->erro_sql = " Campo Data do Log nao Informado.";
         $this->erro_campo = "q50_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q50_data_dia"])){ 
         $sql  .= $virgula." q50_data = null ";
         $virgula = ",";
         if(trim($this->q50_data) == null ){ 
           $this->erro_sql = " Campo Data do Log nao Informado.";
           $this->erro_campo = "q50_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q50_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_hora"])){ 
       $sql  .= $virgula." q50_hora = '$this->q50_hora' ";
       $virgula = ",";
       if(trim($this->q50_hora) == null ){ 
         $this->erro_sql = " Campo Hora do Log nao Informado.";
         $this->erro_campo = "q50_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_arquivo"])){ 
       $sql  .= $virgula." q50_arquivo = '$this->q50_arquivo' ";
       $virgula = ",";
       if(trim($this->q50_arquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo do Log nao Informado.";
         $this->erro_campo = "q50_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q50_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q50_idusuario"])){ 
        if(trim($this->q50_idusuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q50_idusuario"])){ 
           $this->q50_idusuario = "0" ; 
        } 
       $sql  .= $virgula." q50_idusuario = $this->q50_idusuario ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q50_seq!=null){
       $sql .= " q50_seq = $this->q50_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q50_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8711,'$this->q50_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_seq"]))
           $resac = db_query("insert into db_acount values($acount,1486,8711,'".AddSlashes(pg_result($resaco,$conresaco,'q50_seq'))."','$this->q50_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1486,8712,'".AddSlashes(pg_result($resaco,$conresaco,'q50_codigo'))."','$this->q50_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_numpre"]))
           $resac = db_query("insert into db_acount values($acount,1486,8713,'".AddSlashes(pg_result($resaco,$conresaco,'q50_numpre'))."','$this->q50_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_numpar"]))
           $resac = db_query("insert into db_acount values($acount,1486,8714,'".AddSlashes(pg_result($resaco,$conresaco,'q50_numpar'))."','$this->q50_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_valor"]))
           $resac = db_query("insert into db_acount values($acount,1486,8715,'".AddSlashes(pg_result($resaco,$conresaco,'q50_valor'))."','$this->q50_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_ano"]))
           $resac = db_query("insert into db_acount values($acount,1486,8716,'".AddSlashes(pg_result($resaco,$conresaco,'q50_ano'))."','$this->q50_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_mes"]))
           $resac = db_query("insert into db_acount values($acount,1486,8717,'".AddSlashes(pg_result($resaco,$conresaco,'q50_mes'))."','$this->q50_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_histor"]))
           $resac = db_query("insert into db_acount values($acount,1486,8718,'".AddSlashes(pg_result($resaco,$conresaco,'q50_histor'))."','$this->q50_histor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_aliq"]))
           $resac = db_query("insert into db_acount values($acount,1486,8719,'".AddSlashes(pg_result($resaco,$conresaco,'q50_aliq'))."','$this->q50_aliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_bruto"]))
           $resac = db_query("insert into db_acount values($acount,1486,8720,'".AddSlashes(pg_result($resaco,$conresaco,'q50_bruto'))."','$this->q50_bruto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_vlrinf"]))
           $resac = db_query("insert into db_acount values($acount,1486,8721,'".AddSlashes(pg_result($resaco,$conresaco,'q50_vlrinf'))."','$this->q50_vlrinf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_ip"]))
           $resac = db_query("insert into db_acount values($acount,1486,8722,'".AddSlashes(pg_result($resaco,$conresaco,'q50_ip'))."','$this->q50_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_data"]))
           $resac = db_query("insert into db_acount values($acount,1486,8723,'".AddSlashes(pg_result($resaco,$conresaco,'q50_data'))."','$this->q50_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_hora"]))
           $resac = db_query("insert into db_acount values($acount,1486,8724,'".AddSlashes(pg_result($resaco,$conresaco,'q50_hora'))."','$this->q50_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_arquivo"]))
           $resac = db_query("insert into db_acount values($acount,1486,8725,'".AddSlashes(pg_result($resaco,$conresaco,'q50_arquivo'))."','$this->q50_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q50_idusuario"]))
           $resac = db_query("insert into db_acount values($acount,1486,8740,'".AddSlashes(pg_result($resaco,$conresaco,'q50_idusuario'))."','$this->q50_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q50_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q50_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q50_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q50_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q50_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8711,'$q50_seq','E')");
         $resac = db_query("insert into db_acount values($acount,1486,8711,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8712,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8713,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8714,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8715,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8716,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8717,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8718,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_histor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8719,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_aliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8720,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_bruto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8721,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_vlrinf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8722,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8723,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8724,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8725,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1486,8740,'','".AddSlashes(pg_result($resaco,$iresaco,'q50_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issvarlancval
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q50_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q50_seq = $q50_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q50_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q50_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q50_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:issvarlancval";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q50_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issvarlancval ";
     $sql2 = "";
     if($dbwhere==""){
       if($q50_seq!=null ){
         $sql2 .= " where issvarlancval.q50_seq = $q50_seq "; 
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
   function sql_query_file ( $q50_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issvarlancval ";
     $sql2 = "";
     if($dbwhere==""){
       if($q50_seq!=null ){
         $sql2 .= " where issvarlancval.q50_seq = $q50_seq "; 
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
