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

//MODULO: agua
//CLASSE DA ENTIDADE aguacortematnumpre
class cl_aguacortematnumpre { 
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
   var $x44_codcortematnumpre = 0; 
   var $x44_codcortemat = 0; 
   var $x44_numpre = 0; 
   var $x44_numpar = 0; 
   var $x44_dtvenc_dia = null; 
   var $x44_dtvenc_mes = null; 
   var $x44_dtvenc_ano = null; 
   var $x44_dtvenc = null; 
   var $x44_tipo = 0; 
   var $x44_vlrhis = 0; 
   var $x44_vlrcor = 0; 
   var $x44_juros = 0; 
   var $x44_multa = 0; 
   var $x44_desconto = 0; 
   var $x44_receit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x44_codcortematnumpre = int4 = Corte Matricula Numpre 
                 x44_codcortemat = int4 = Corte Matricula 
                 x44_numpre = int4 = Numpre 
                 x44_numpar = int4 = Parcela 
                 x44_dtvenc = date = Vencimento 
                 x44_tipo = int4 = Tipo 
                 x44_vlrhis = float8 = Valor Historico 
                 x44_vlrcor = float8 = Valor Corrigido 
                 x44_juros = float8 = Juros 
                 x44_multa = float8 = Multa 
                 x44_desconto = float8 = Desconto 
                 x44_receit = int4 = Receita 
                 ";
   //funcao construtor da classe 
   function cl_aguacortematnumpre() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacortematnumpre"); 
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
       $this->x44_codcortematnumpre = ($this->x44_codcortematnumpre == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_codcortematnumpre"]:$this->x44_codcortematnumpre);
       $this->x44_codcortemat = ($this->x44_codcortemat == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_codcortemat"]:$this->x44_codcortemat);
       $this->x44_numpre = ($this->x44_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_numpre"]:$this->x44_numpre);
       $this->x44_numpar = ($this->x44_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_numpar"]:$this->x44_numpar);
       if($this->x44_dtvenc == ""){
         $this->x44_dtvenc_dia = ($this->x44_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_dtvenc_dia"]:$this->x44_dtvenc_dia);
         $this->x44_dtvenc_mes = ($this->x44_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_dtvenc_mes"]:$this->x44_dtvenc_mes);
         $this->x44_dtvenc_ano = ($this->x44_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_dtvenc_ano"]:$this->x44_dtvenc_ano);
         if($this->x44_dtvenc_dia != ""){
            $this->x44_dtvenc = $this->x44_dtvenc_ano."-".$this->x44_dtvenc_mes."-".$this->x44_dtvenc_dia;
         }
       }
       $this->x44_tipo = ($this->x44_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_tipo"]:$this->x44_tipo);
       $this->x44_vlrhis = ($this->x44_vlrhis == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_vlrhis"]:$this->x44_vlrhis);
       $this->x44_vlrcor = ($this->x44_vlrcor == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_vlrcor"]:$this->x44_vlrcor);
       $this->x44_juros = ($this->x44_juros == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_juros"]:$this->x44_juros);
       $this->x44_multa = ($this->x44_multa == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_multa"]:$this->x44_multa);
       $this->x44_desconto = ($this->x44_desconto == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_desconto"]:$this->x44_desconto);
       $this->x44_receit = ($this->x44_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_receit"]:$this->x44_receit);
     }else{
       $this->x44_codcortematnumpre = ($this->x44_codcortematnumpre == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_codcortematnumpre"]:$this->x44_codcortematnumpre);
       $this->x44_codcortemat = ($this->x44_codcortemat == ""?@$GLOBALS["HTTP_POST_VARS"]["x44_codcortemat"]:$this->x44_codcortemat);
     }
   }
   // funcao para inclusao
   function incluir ($x44_codcortematnumpre){ 
      $this->atualizacampos();
     if($this->x44_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "x44_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x44_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "x44_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x44_dtvenc == null ){ 
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "x44_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x44_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "x44_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x44_vlrhis == null ){ 
       $this->erro_sql = " Campo Valor Historico nao Informado.";
       $this->erro_campo = "x44_vlrhis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x44_vlrcor == null ){ 
       $this->erro_sql = " Campo Valor Corrigido nao Informado.";
       $this->erro_campo = "x44_vlrcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x44_juros == null ){ 
       $this->erro_sql = " Campo Juros nao Informado.";
       $this->erro_campo = "x44_juros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x44_multa == null ){ 
       $this->erro_sql = " Campo Multa nao Informado.";
       $this->erro_campo = "x44_multa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x44_desconto == null ){ 
       $this->erro_sql = " Campo Desconto nao Informado.";
       $this->erro_campo = "x44_desconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x44_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "x44_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x44_codcortematnumpre == "" || $x44_codcortematnumpre == null ){
       $result = db_query("select nextval('aguacortematnumpre_x44_codcortematnumpre_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacortematnumpre_x44_codcortematnumpre_seq do campo: x44_codcortematnumpre"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x44_codcortematnumpre = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguacortematnumpre_x44_codcortematnumpre_seq");
       if(($result != false) && (pg_result($result,0,0) < $x44_codcortematnumpre)){
         $this->erro_sql = " Campo x44_codcortematnumpre maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x44_codcortematnumpre = $x44_codcortematnumpre; 
       }
     }
     if(($this->x44_codcortematnumpre == null) || ($this->x44_codcortematnumpre == "") ){ 
       $this->erro_sql = " Campo x44_codcortematnumpre nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacortematnumpre(
                                       x44_codcortematnumpre 
                                      ,x44_codcortemat 
                                      ,x44_numpre 
                                      ,x44_numpar 
                                      ,x44_dtvenc 
                                      ,x44_tipo 
                                      ,x44_vlrhis 
                                      ,x44_vlrcor 
                                      ,x44_juros 
                                      ,x44_multa 
                                      ,x44_desconto 
                                      ,x44_receit 
                       )
                values (
                                $this->x44_codcortematnumpre 
                               ,$this->x44_codcortemat 
                               ,$this->x44_numpre 
                               ,$this->x44_numpar 
                               ,".($this->x44_dtvenc == "null" || $this->x44_dtvenc == ""?"null":"'".$this->x44_dtvenc."'")." 
                               ,$this->x44_tipo 
                               ,$this->x44_vlrhis 
                               ,$this->x44_vlrcor 
                               ,$this->x44_juros 
                               ,$this->x44_multa 
                               ,$this->x44_desconto 
                               ,$this->x44_receit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguacortematnumpre ($this->x44_codcortematnumpre) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguacortematnumpre já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguacortematnumpre ($this->x44_codcortematnumpre) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x44_codcortematnumpre;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x44_codcortematnumpre));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8549,'$this->x44_codcortematnumpre','I')");
       $resac = db_query("insert into db_acount values($acount,1455,8549,'','".AddSlashes(pg_result($resaco,0,'x44_codcortematnumpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1455,8550,'','".AddSlashes(pg_result($resaco,0,'x44_codcortemat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1455,8551,'','".AddSlashes(pg_result($resaco,0,'x44_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1455,8552,'','".AddSlashes(pg_result($resaco,0,'x44_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1455,8561,'','".AddSlashes(pg_result($resaco,0,'x44_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1455,8562,'','".AddSlashes(pg_result($resaco,0,'x44_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1455,8563,'','".AddSlashes(pg_result($resaco,0,'x44_vlrhis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1455,8564,'','".AddSlashes(pg_result($resaco,0,'x44_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1455,8565,'','".AddSlashes(pg_result($resaco,0,'x44_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1455,8566,'','".AddSlashes(pg_result($resaco,0,'x44_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1455,8567,'','".AddSlashes(pg_result($resaco,0,'x44_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1455,8569,'','".AddSlashes(pg_result($resaco,0,'x44_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x44_codcortematnumpre=null) { 
      $this->atualizacampos();
     $sql = " update aguacortematnumpre set ";
     $virgula = "";
     if(trim($this->x44_codcortematnumpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x44_codcortematnumpre"])){ 
       $sql  .= $virgula." x44_codcortematnumpre = $this->x44_codcortematnumpre ";
       $virgula = ",";
       if(trim($this->x44_codcortematnumpre) == null ){ 
         $this->erro_sql = " Campo Corte Matricula Numpre nao Informado.";
         $this->erro_campo = "x44_codcortematnumpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x44_codcortemat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x44_codcortemat"])){ 
       $sql  .= $virgula." x44_codcortemat = $this->x44_codcortemat ";
       $virgula = ",";
       if(trim($this->x44_codcortemat) == null ){ 
         $this->erro_sql = " Campo Corte Matricula nao Informado.";
         $this->erro_campo = "x44_codcortemat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x44_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x44_numpre"])){ 
       $sql  .= $virgula." x44_numpre = $this->x44_numpre ";
       $virgula = ",";
       if(trim($this->x44_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "x44_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x44_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x44_numpar"])){ 
       $sql  .= $virgula." x44_numpar = $this->x44_numpar ";
       $virgula = ",";
       if(trim($this->x44_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "x44_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x44_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x44_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x44_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." x44_dtvenc = '$this->x44_dtvenc' ";
       $virgula = ",";
       if(trim($this->x44_dtvenc) == null ){ 
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "x44_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x44_dtvenc_dia"])){ 
         $sql  .= $virgula." x44_dtvenc = null ";
         $virgula = ",";
         if(trim($this->x44_dtvenc) == null ){ 
           $this->erro_sql = " Campo Vencimento nao Informado.";
           $this->erro_campo = "x44_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x44_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x44_tipo"])){ 
       $sql  .= $virgula." x44_tipo = $this->x44_tipo ";
       $virgula = ",";
       if(trim($this->x44_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "x44_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x44_vlrhis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x44_vlrhis"])){ 
       $sql  .= $virgula." x44_vlrhis = $this->x44_vlrhis ";
       $virgula = ",";
       if(trim($this->x44_vlrhis) == null ){ 
         $this->erro_sql = " Campo Valor Historico nao Informado.";
         $this->erro_campo = "x44_vlrhis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x44_vlrcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x44_vlrcor"])){ 
       $sql  .= $virgula." x44_vlrcor = $this->x44_vlrcor ";
       $virgula = ",";
       if(trim($this->x44_vlrcor) == null ){ 
         $this->erro_sql = " Campo Valor Corrigido nao Informado.";
         $this->erro_campo = "x44_vlrcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x44_juros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x44_juros"])){ 
       $sql  .= $virgula." x44_juros = $this->x44_juros ";
       $virgula = ",";
       if(trim($this->x44_juros) == null ){ 
         $this->erro_sql = " Campo Juros nao Informado.";
         $this->erro_campo = "x44_juros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x44_multa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x44_multa"])){ 
       $sql  .= $virgula." x44_multa = $this->x44_multa ";
       $virgula = ",";
       if(trim($this->x44_multa) == null ){ 
         $this->erro_sql = " Campo Multa nao Informado.";
         $this->erro_campo = "x44_multa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x44_desconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x44_desconto"])){ 
       $sql  .= $virgula." x44_desconto = $this->x44_desconto ";
       $virgula = ",";
       if(trim($this->x44_desconto) == null ){ 
         $this->erro_sql = " Campo Desconto nao Informado.";
         $this->erro_campo = "x44_desconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x44_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x44_receit"])){ 
       $sql  .= $virgula." x44_receit = $this->x44_receit ";
       $virgula = ",";
       if(trim($this->x44_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "x44_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x44_codcortematnumpre!=null){
       $sql .= " x44_codcortematnumpre = $this->x44_codcortematnumpre";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x44_codcortematnumpre));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8549,'$this->x44_codcortematnumpre','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x44_codcortematnumpre"]))
           $resac = db_query("insert into db_acount values($acount,1455,8549,'".AddSlashes(pg_result($resaco,$conresaco,'x44_codcortematnumpre'))."','$this->x44_codcortematnumpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x44_codcortemat"]))
           $resac = db_query("insert into db_acount values($acount,1455,8550,'".AddSlashes(pg_result($resaco,$conresaco,'x44_codcortemat'))."','$this->x44_codcortemat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x44_numpre"]))
           $resac = db_query("insert into db_acount values($acount,1455,8551,'".AddSlashes(pg_result($resaco,$conresaco,'x44_numpre'))."','$this->x44_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x44_numpar"]))
           $resac = db_query("insert into db_acount values($acount,1455,8552,'".AddSlashes(pg_result($resaco,$conresaco,'x44_numpar'))."','$this->x44_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x44_dtvenc"]))
           $resac = db_query("insert into db_acount values($acount,1455,8561,'".AddSlashes(pg_result($resaco,$conresaco,'x44_dtvenc'))."','$this->x44_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x44_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1455,8562,'".AddSlashes(pg_result($resaco,$conresaco,'x44_tipo'))."','$this->x44_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x44_vlrhis"]))
           $resac = db_query("insert into db_acount values($acount,1455,8563,'".AddSlashes(pg_result($resaco,$conresaco,'x44_vlrhis'))."','$this->x44_vlrhis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x44_vlrcor"]))
           $resac = db_query("insert into db_acount values($acount,1455,8564,'".AddSlashes(pg_result($resaco,$conresaco,'x44_vlrcor'))."','$this->x44_vlrcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x44_juros"]))
           $resac = db_query("insert into db_acount values($acount,1455,8565,'".AddSlashes(pg_result($resaco,$conresaco,'x44_juros'))."','$this->x44_juros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x44_multa"]))
           $resac = db_query("insert into db_acount values($acount,1455,8566,'".AddSlashes(pg_result($resaco,$conresaco,'x44_multa'))."','$this->x44_multa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x44_desconto"]))
           $resac = db_query("insert into db_acount values($acount,1455,8567,'".AddSlashes(pg_result($resaco,$conresaco,'x44_desconto'))."','$this->x44_desconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x44_receit"]))
           $resac = db_query("insert into db_acount values($acount,1455,8569,'".AddSlashes(pg_result($resaco,$conresaco,'x44_receit'))."','$this->x44_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacortematnumpre nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x44_codcortematnumpre;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacortematnumpre nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x44_codcortematnumpre;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x44_codcortematnumpre;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x44_codcortematnumpre=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x44_codcortematnumpre));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8549,'$x44_codcortematnumpre','E')");
         $resac = db_query("insert into db_acount values($acount,1455,8549,'','".AddSlashes(pg_result($resaco,$iresaco,'x44_codcortematnumpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1455,8550,'','".AddSlashes(pg_result($resaco,$iresaco,'x44_codcortemat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1455,8551,'','".AddSlashes(pg_result($resaco,$iresaco,'x44_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1455,8552,'','".AddSlashes(pg_result($resaco,$iresaco,'x44_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1455,8561,'','".AddSlashes(pg_result($resaco,$iresaco,'x44_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1455,8562,'','".AddSlashes(pg_result($resaco,$iresaco,'x44_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1455,8563,'','".AddSlashes(pg_result($resaco,$iresaco,'x44_vlrhis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1455,8564,'','".AddSlashes(pg_result($resaco,$iresaco,'x44_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1455,8565,'','".AddSlashes(pg_result($resaco,$iresaco,'x44_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1455,8566,'','".AddSlashes(pg_result($resaco,$iresaco,'x44_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1455,8567,'','".AddSlashes(pg_result($resaco,$iresaco,'x44_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1455,8569,'','".AddSlashes(pg_result($resaco,$iresaco,'x44_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguacortematnumpre
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x44_codcortematnumpre != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x44_codcortematnumpre = $x44_codcortematnumpre ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacortematnumpre nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x44_codcortematnumpre;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacortematnumpre nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x44_codcortematnumpre;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x44_codcortematnumpre;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacortematnumpre";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x44_codcortematnumpre=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacortematnumpre ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = aguacortematnumpre.x44_receit";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = aguacortematnumpre.x44_tipo";
     $sql .= "      inner join aguacortemat  on  aguacortemat.x41_codcortemat = aguacortematnumpre.x44_codcortemat";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguacortemat.x41_matric";
     $sql .= "      inner join aguacorte  as a on   a.x40_codcorte = aguacortemat.x41_codcorte";
     $sql2 = "";
     if($dbwhere==""){
       if($x44_codcortematnumpre!=null ){
         $sql2 .= " where aguacortematnumpre.x44_codcortematnumpre = $x44_codcortematnumpre "; 
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
   function sql_query_file ( $x44_codcortematnumpre=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacortematnumpre ";
     $sql2 = "";
     if($dbwhere==""){
       if($x44_codcortematnumpre!=null ){
         $sql2 .= " where aguacortematnumpre.x44_codcortematnumpre = $x44_codcortematnumpre "; 
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